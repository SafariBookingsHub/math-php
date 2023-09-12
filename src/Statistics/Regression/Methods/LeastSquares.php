<?php

    namespace MathPHP\Statistics\Regression\Methods;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Exception;
    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Functions\Map\Multi;
    use MathPHP\Functions\Map\Single;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use MathPHP\Probability\Distribution\Continuous\F;
    use MathPHP\Probability\Distribution\Continuous\StudentT;
    use MathPHP\Statistics\RandomVariable;

    use function array_map;
    use function array_sum;
    use function is_float;
    use function is_int;
    use function sqrt;

    trait LeastSquares {
        /**
         * Regression ys
         * Since the actual xs may be translated for regression, we need to keep these
         * handy for regression statistics.
         *
         * @var array<float>
         */
        private array $reg_ys;

        /**
         * Regression xs
         * Since the actual xs may be translated for regression, we need to keep these
         * handy for regression statistics.
         *
         * @var array<float>
         */
        private array $reg_xs;

        /**
         * Regression Yhat
         * The Yhat for the regression xs.
         *
         * @var array<float>
         */
        private array $reg_Yhat;

        /**
         * Projection Matrix
         * https://en.wikipedia.org/wiki/Projection_matrix
         *
         * @var NumericMatrix
         */
        private NumericMatrix $reg_P;

        /** @var float */
        private float $fit_constant;

        /** @var int */
        private int $p;

        /** @var int Degrees of freedom */
        private int $ν;

        /** @var NumericMatrix */
        private NumericMatrix $⟮XᵀX⟯⁻¹;

        /**
         * Linear least squares fitting using Matrix algebra (Polynomial).
         *
         * Generalizing from a straight line (first degree polynomial) to a kᵗʰ degree polynomial:
         *  y = a₀ + a₁x + ⋯ + akxᵏ
         *
         * Leads to equations in matrix form:
         *  [n    Σxᵢ   ⋯  Σxᵢᵏ  ] [a₀]   [Σyᵢ   ]
         *  [Σxᵢ  Σxᵢ²  ⋯  Σxᵢᵏ⁺¹] [a₁]   [Σxᵢyᵢ ]
         *  [ ⋮     ⋮    ⋱  ⋮    ] [ ⋮ ] = [ ⋮    ]
         *  [Σxᵢᵏ Σxᵢᵏ⁺¹ ⋯ Σxᵢ²ᵏ ] [ak]   [Σxᵢᵏyᵢ]
         *
         * This is a Vandermonde matrix:
         *  [1 x₁ ⋯ x₁ᵏ] [a₀]   [y₁]
         *  [1 x₂ ⋯ x₂ᵏ] [a₁]   [y₂]
         *  [⋮  ⋮  ⋱ ⋮ ] [ ⋮ ] = [ ⋮]
         *  [1 xn ⋯ xnᵏ] [ak]   [yn]
         *
         * Can write as equation:
         *  y = Xa
         *
         * Solve by premultiplying by transpose Xᵀ:
         *  Xᵀy = XᵀXa
         *
         * Invert to yield vector solution:
         *  a = (XᵀX)⁻¹Xᵀy
         *
         * (http://mathworld.wolfram.com/LeastSquaresFittingPolynomial.html)
         *
         * For reference, the traditional way to do least squares:
         *        _ _   __
         *        x y - xy        _    _
         *   m = _________    b = y - mx
         *        _     __
         *       (x)² - x²
         *
         * @param array<float> $ys           y values
         * @param array<float> $xs           x values
         * @param int          $order        The order of the polynomial. 1 = linear, 2 = x², etc
         * @param int          $fit_constant '1' if we are fitting a constant to the regression.
         *
         * @return NumericMatrix [[m], [b]]
         *
         * @throws Exception\MathException
         */
        public function leastSquares(
            array $ys,
            array $xs,
            int $order = 1,
            int $fit_constant = 1
        ): NumericMatrix {
            $this->reg_ys = $ys;
            $this->reg_xs = $xs;
            $this->fit_constant = $fit_constant;
            $this->p = $order;
            $this->ν = $this->n - $this->p - $this->fit_constant;

            if ($this->ν <= 0)
                throw new Exception\BadDataException('Degrees of freedom ν must be > 0. Computed to be '
                    .$this->ν);

            // y = Xa
            $X = $this->createDesignMatrix($xs);
            /** @var NumericMatrix $y */
            $y = MatrixFactory::createFromColumnVector($ys);

            // a = (XᵀX)⁻¹Xᵀy
            $Xᵀ = $X->transpose();
            $this->⟮XᵀX⟯⁻¹ = $Xᵀ->multiply($X)->inverse();
            $temp_matrix = $this->⟮XᵀX⟯⁻¹->multiply($Xᵀ);
            $this->reg_P = $X->multiply($temp_matrix);
            $β_hat = $temp_matrix->multiply($y);

            $this->reg_Yhat = $X->multiply($β_hat)->getColumn(0);

            return $β_hat;
        }

        /**
         * The Design Matrix contains all the independent variables needed for the least squares regression
         *
         * https://en.wikipedia.org/wiki/Design_matrix
         *
         * @param mixed $xs
         *
         * @return NumericMatrix (Vandermonde)
         *
         * @throws Exception\BadDataException
         * @throws Exception\IncorrectTypeException
         * @throws Exception\MathException
         * @throws Exception\MatrixException
         */
        public function createDesignMatrix(mixed $xs): NumericMatrix
        {
            if (is_int($xs) || is_float($xs))
                $xs = [$xs];

            /** @var array<float> $xs */
            $X = MatrixFactory::vandermonde($xs, $this->p + 1);
            if ($this->fit_constant == 0)
                $X = $X->columnExclude(0);

            return $X;
        }

        /**
         * Project matrix (influence matrix, hat matrix H)
         * Maps the vector of response values (dependent variable values) to the vector of fitted values (or predicted values).
         * The diagonal elements of the projection matrix are the leverages.
         * https://en.wikipedia.org/wiki/Projection_matrix
         *
         * H = X⟮XᵀX⟯⁻¹Xᵀ
         *   where X is the design matrix
         *
         * @return NumericMatrix
         */
        public function getProjectionMatrix(): NumericMatrix
        {
            return $this->reg_P;
        }

        /**
         * Mean of squares total
         * MSTO = SSOT / (n - 1)
         *
         * @return float
         *
         * @throws Exception\BadDataException
         */
        public function meanSquareTotal(): float
        {
            return $this->sumOfSquaresTotal() / ($this->n
                    - $this->fit_constant);
        }

        /**************************************************************************
         * Sum Of Squares
         *************************************************************************/

        /**
         * SStot - The total Sum Squares
         *
         * the sum, over all observations, of the squared differences of
         * each observation from the overall mean.
         * https://en.wikipedia.org/wiki/Total_sum_of_squares
         *
         * For Simple Linear Regression
         * SStot = ∑(yᵢ - ȳ)²
         *
         * For Regression through a point
         * SStot = ∑yᵢ²
         *
         * @return float
         *
         * @throws Exception\BadDataException
         */
        public function sumOfSquaresTotal(): float
        {
            return $this->sumOfSquaresResidual()
                + $this->sumOfSquaresRegression();
        }

        /**
         * SSres - The Sum Squares of the residuals (RSS - Residual sum of squares)
         *
         * The sum of the squares of residuals (deviations predicted from actual
         * empirical values of data). It is a measure of the discrepancy between
         * the data and an estimation model.
         * https://en.wikipedia.org/wiki/Residual_sum_of_squares
         *
         * SSres = ∑(yᵢ - f(xᵢ))²
         *       = ∑(yᵢ - ŷᵢ)²
         *
         *  where yᵢ is an observed value
         *        ŷᵢ is a value predicted by the regression model
         *
         * @return float
         */
        public function sumOfSquaresResidual(): float
        {
            $Ŷ = $this->reg_Yhat;

            return array_sum(array_map(
                fn($yᵢ, $ŷᵢ) => ($yᵢ - $ŷᵢ) ** 2,
                $this->reg_ys,
                $Ŷ
            ));
        }

        /**
         * SSreg - The Sum Squares of the regression (Explained sum of squares)
         *
         * The sum of the squares of the deviations of the predicted values from
         * the mean value of a response variable, in a standard regression model.
         * https://en.wikipedia.org/wiki/Explained_sum_of_squares
         *
         * SSreg = ∑(ŷᵢ - ȳ)²
         * When a constant is fit to the regression, the average of y = average of ŷ.
         *
         * In the case where the constant is not fit, we use the sum of squares of the predicted value
         * SSreg = ∑ŷᵢ²
         *
         * @return float
         *
         * @throws Exception\BadDataException
         */
        public function sumOfSquaresRegression(): float
        {
            if ($this->fit_constant == 1)
                return RandomVariable::sumOfSquaresDeviations($this->yHat());

            return array_sum(Single::square($this->reg_Yhat));
        }

        /***************************************************************************
         * Mean Square Errors
         *
         * The mean square errors are the sum of squares divided by their
         * individual degrees of freedom.
         *
         * Source    |     df
         * ----------|--------------
         * SSTO      |    n - 1
         * SSE       |    n - p - 1
         * SSR       |    p
         **************************************************************************/

        /**
         * Error Standard Deviation
         *
         * Also called the standard error of the residuals
         *
         * @return float
         */
        public function errorSd(): float
        {
            return sqrt($this->meanSquareResidual());
        }

        /**
         * Mean of squares for error
         * MSE = SSₑ / ν
         *
         * @return float
         */
        public function meanSquareResidual(): float
        {
            $ν = $this->ν;
            $SSₑ = $this->sumOfSquaresResidual();

            return $SSₑ / $ν;
        }

        /**
         * The degrees of freedom of the regression
         *
         * @return float
         */
        public function degreesOfFreedom(): float
        {
            return $this->ν;
        }

        /**
         * Cook's Distance
         * A measures of the influence of each data point on the regression.
         * Points with excessive influence may be outliers, or may warrent a closer look.
         *
         * https://en.wikipedia.org/wiki/Cook%27s_distance
         *
         *           _         _
         *      eᵢ² |     hᵢ    |
         * Dᵢ = --- | --------- |
         *      s²p |_(1 - hᵢ)²_|
         *
         *   where s ≡ (n - p)⁻¹eᵀ  (mean square residuals)
         *         e is the mean square error of the residual model
         *
         * @return array<float>
         *
         * @throws BadDataException
         */
        public function cooksD(): array
        {
            $e = $this->residuals();
            $h = $this->leverages();
            $mse = $this->meanSquareResidual();
            $p = $this->p + $this->fit_constant;

            return array_map(
                fn($eᵢ, $hᵢ) => (($eᵢ ** 2) / $mse / $p) * ($hᵢ / (1 - $hᵢ) ** 2),
                $e,
                $h
            );
        }

        /**
         * Get the regression residuals
         * eᵢ = yᵢ - ŷᵢ
         * or in matrix form
         * e = (I - H)y
         *
         * @return array<float>
         *
         * @throws BadDataException
         */
        public function residuals(): array
        {
            return Multi::subtract($this->reg_ys, $this->reg_Yhat);
        }

        /**
         * Regression Leverages
         * A measure of how far away the independent variable values of an observation are from those of the other observations.
         * https://en.wikipedia.org/wiki/Leverage_(statistics)
         *
         * Leverage score for the i-th data unit is defined as:
         * hᵢᵢ = [H]ᵢᵢ
         * which is the i-th diagonal element of the project matrix H,
         * where H = X⟮XᵀX⟯⁻¹Xᵀ where X is the design matrix.
         *
         * @return array<int|float>
         */
        public function leverages(): array
        {
            return $this->reg_P->getDiagonalElements();
        }

        /**
         * DFFITS
         * Measures the effect on the regression if each data point is excluded.
         * https://en.wikipedia.org/wiki/DFFITS
         *
         *          ŷᵢ - ŷᵢ₍ᵢ₎
         * DFFITS = ----------
         *          s₍ᵢ₎ √hᵢᵢ
         *
         *   where ŷᵢ    is the prediction for point i with i included in the regression
         *         ŷᵢ₍ᵢ₎ is the prediction for point i without i included in the regression
         *         s₍ᵢ₎  is the standard error estimated without the point in question
         *         hᵢᵢ   is the leverage for the point
         *
         * Putting it another way:
         *
         * sᵢ is the studentized residual
         *
         *             eᵢ
         * sᵢ = --------------
         *        √(MSₑ(1 - hᵢ))
         *
         *   where eᵢ  is the residual
         *         MSₑ is the mean squares residual
         *
         * Then, s₍ᵢ₎ is the studentized residual with the i-th observation removed:
         *
         *               eᵢ
         * s₍ᵢ₎ = -----------------
         *        √(MSₑ₍ᵢ₎(1 - hᵢ))
         *
         * where
         *          _                _
         *         |           eᵢ²    |   ν
         * MSₑ₍ᵢ₎ =|  MSₑ - --------  | -----
         *         |_       (1 - h)ν _| ν - 1
         *
         * Then,
         *                  ______
         *                 /  hᵢ
         * DFFITS = s₍ᵢ₎  / ------
         *               √  1 - hᵢ
         *
         * @return array<float>
         *
         * @throws BadDataException
         */
        public function dffits(): array
        {
            $ν = $this->ν;
            $h = $this->leverages();
            $e = $this->residuals();
            $MSₑ = $this->meanSquareResidual();

            // Mean square residuals with the the i-th observation removed
            $MSₑ₍ᵢ₎ = array_map(
                fn($eᵢ, $hᵢ) => ($MSₑ - (($eᵢ ** 2) / ((1 - $hᵢ) * $ν))) * ($ν / ($ν
                            - 1)),
                $e,
                $h
            );

            // Studentized residual with the i-th observation removed
            $s = array_map(
                fn($eᵢ, $mseᵢ, $hᵢ) => $eᵢ / sqrt($mseᵢ * (1 - $hᵢ)),
                $e,
                $MSₑ₍ᵢ₎,
                $h
            );

            return array_map(
                fn($s₍ᵢ₎, $hᵢ) => $s₍ᵢ₎ * sqrt($hᵢ / (1 - $hᵢ)),
                $s,
                $h
            );
        }

        /**
         * R - correlation coefficient
         * Convenience wrapper for correlationCoefficient
         *
         * @return float
         *
         * @throws BadDataException
         */
        public function r(): float
        {
            return $this->correlationCoefficient();
        }

        /**
         * R - correlation coefficient (Pearson's r)
         *
         * A measure of the strength and direction of the linear relationship
         * between two variables
         * that is defined as the (sample) covariance of the variables
         * divided by the product of their (sample) standard deviations.
         *
         *      n∑⟮xy⟯ − ∑⟮x⟯∑⟮y⟯
         * --------------------------------
         * √［（n∑x² − ⟮∑x⟯²）（n∑y² − ⟮∑y⟯²）］
         *
         * @return float
         *
         * @throws BadDataException
         */
        public function correlationCoefficient(): float
        {
            return sqrt($this->coefficientOfDetermination());
        }

        /**
         * R² - coefficient of determination
         *
         * Indicates the proportion of the variance in the dependent variable
         * that is predictable from the independent variable.
         * Range of 0 - 1. Close to 1 means the regression line is a good fit
         * https://en.wikipedia.org/wiki/Coefficient_of_determination
         *
         * @return float
         *
         * @throws BadDataException
         */
        public function coefficientOfDetermination(): float
        {
            return $this->sumOfSquaresRegression()
                / ($this->sumOfSquaresRegression()
                    + $this->sumOfSquaresResidual());
        }

        /**
         * R² - coefficient of determination
         * Convenience wrapper for coefficientOfDetermination
         *
         * @return float
         *
         * @throws BadDataException
         */
        public function r2(): float
        {
            return $this->coefficientOfDetermination();
        }

        /**
         * The probabilty associated with each parameter's t value
         *
         * t probability = Student's T CDF(t,ν)
         *
         *  where:
         *    t = t value
         *    ν = n - p - alpha  degrees of freedom
         *
         *  alpha = 1 if the regression includes a constant term
         *
         * @return array{m: float, b: float} [m => p, b => p]
         *
         * @throws BadParameterException
         * @throws IncorrectTypeException
         */
        #[ArrayShape(['m' => "float", 'b' => "float"])] public function tProbability(): array
        {
            $ν = $this->ν;
            $t = $this->tValues();

            $studentT = new StudentT($ν);

            return [
                'm' => $studentT->cdf($t['m']),
                'b' => $studentT->cdf($t['b']),
            ];
        }

        /**
         * The t values associated with each of the regression parameters (coefficients)
         *
         *       β
         * t = -----
         *     se(β)
         *
         *  where:
         *    β     = regression parameter (coefficient)
         *    se(β) = standard error of the regression parameter (coefficient)
         *
         * @return array{m: float, b: float} [m => t, b => t]
         *
         * @throws BadParameterException
         * @throws IncorrectTypeException
         */
        #[ArrayShape(['m' => "float|int", 'b' => "float|int"])] public function tValues(): array
        {
            $se = $this->standardErrors();
            $m = $this->parameters[1];
            $b = $this->parameters[0];

            return [
                'm' => $m / $se['m'],
                'b' => $b / $se['b'],
            ];
        }

        /**
         * Standard error of the regression parameters (coefficients)
         *
         *              _________
         *             /  ∑eᵢ²
         *            /  -----
         * se(m) =   /     ν
         *          /  ---------
         *         √   ∑⟮xᵢ - μ⟯²
         *
         *  where
         *    eᵢ = residual (difference between observed value and value predicted by the model)
         *    ν  = n - 2  degrees of freedom
         *
         *           ______
         *          / ∑xᵢ²
         * se(b) = /  ----
         *        √    n
         *
         * @return array{m: float, b: float} [m => se(m), b => se(b)]
         *
         * @throws Exception\BadParameterException
         * @throws Exception\IncorrectTypeException
         */
        #[ArrayShape(['m' => "float", 'b' => "float"])] public function standardErrors(): array
        {
            $⟮XᵀX⟯⁻¹ = $this->⟮XᵀX⟯⁻¹;
            $σ² = $this->meanSquareResidual();

            $standard_error_matrix = $⟮XᵀX⟯⁻¹->scalarMultiply($σ²);
            $standard_error_array
                = Single::sqrt($standard_error_matrix->getDiagonalElements());

            return [
                'm' => $standard_error_array[1],
                'b' => $standard_error_array[0],
            ];
        }

        /**
         * The probabilty associated with the regression F Statistic
         *
         * F probability = F distribution CDF(F,d₁,d₂)
         *
         *  where:
         *    F  = F statistic
         *    d₁ = degrees of freedom 1
         *    d₂ = degrees of freedom 2
         *
         *    ν  = degrees of freedom
         *
         * @return float
         *
         * @throws BadDataException
         */
        public function fProbability(): float
        {
            $F = $this->fStatistic();
            $n = $this->n;

            // Degrees of freedom
            // Need to make sure the 1 in $d₁ should not be $this->fit_parameters;
            $ν = $this->ν;
            $d₁ = $n - $ν - 1;
            $d₂ = $ν;

            $fDist = new F($d₁, $d₂);

            return $fDist->cdf($F);
        }

        /**
         * The F statistic of the regression (F test)
         *
         *      MSm      SSᵣ/p
         * F₀ = --- = -----------
         *      MSₑ   SSₑ/(n - p - α)
         *
         *  where:
         *    MSm = mean square model (regression mean square) = SSᵣ / df(SSᵣ) = SSᵣ/p
         *    MSₑ = mean square error (estimate of variance σ² of the random error)
         *        = SSₑ/(n - p - α)
         *    p   = the order of the fitted polynomial
         *    α   = 1 if the model includes a constant term, 0 otherwise. (p+α = total number of model parameters)
         *    SSᵣ = sum of squares of the regression
         *    SSₑ = sum of squares of residuals
         *
         * @return float
         *
         * @throws BadDataException
         */
        public function fStatistic(): float
        {
            return $this->meanSquareRegression() / $this->meanSquareResidual();
        }

        /**
         * Mean square regression
         * MSR = SSᵣ / p
         *
         * @return float
         *
         * @throws Exception\BadDataException
         */
        public function meanSquareRegression(): float
        {
            $p = $this->p;
            $SSᵣ = $this->sumOfSquaresRegression();

            return $SSᵣ / $p;
        }

        /**
         * The confidence interval of the regression for Simple Linear Regression
         *                      ______________
         *                     /1   (x - x̄)²
         * CI(x,p) = t * sy * / - + --------
         *                   √  n     SSx
         *
         * Where:
         *   t is the critical t for the p value
         *   sy is the estimated standard deviation of y
         *   n is the number of data points
         *   x̄ is the average of the x values
         *   SSx = ∑(x - x̄)²
         *
         * If $p = .05, then we can say we are 95% confidence the actual regression line
         * will be within an interval of evaluate($x) ± CI($x, .05).
         *
         * @param float $x
         * @param float $p :  0 < p < 1 The P value to use
         *
         * @return float
         *
         * @throws Exception\MatrixException
         * @throws Exception\IncorrectTypeException
         */
        public function ci(float $x, float $p): float
        {
            $V = $this->regressionVariance($x);
            $σ² = $this->meanSquareResidual();

            // The t-value
            $studentT = new StudentT($this->ν);
            $t = $studentT->inverse2Tails($p);

            return $t * sqrt($σ² * $V);
        }

        /**
         * Regression variance
         *
         * @param float $x
         *
         * @return float
         *
         */
        public function regressionVariance(float $x): float
        {
            try
            {
                $X = $this->createDesignMatrix($x);
            } catch (BadDataException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
            $⟮XᵀX⟯⁻¹ = $this->⟮XᵀX⟯⁻¹;
            try
            {
                $M = $X->multiply($⟮XᵀX⟯⁻¹)->multiply($X->transpose());
            } catch (IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            return $M[0][0];
        }

        /**
         * The prediction interval of the regression
         *                        _________________
         *                       /1    1   (x - x̄)²
         * PI(x,p,q) = t * sy * / - +  - + --------
         *                     √  q    n     SSx
         *
         * Where:
         *   t is the critical t for the p value
         *   sy is the estimated standard deviation of y
         *   q is the number of replications
         *   n is the number of data points
         *   x̄ is the average of the x values
         *   SSx = ∑(x - x̄)²
         *
         * If $p = .05, then we can say we are 95% confidence that the future averages of $q trials at $x
         * will be within an interval of evaluate($x) ± PI($x, .05, $q).
         *
         * @param float $x
         * @param float $p 0 < p < 1 The P value to use
         * @param int   $q Number of trials
         *
         * @return float
         *
         * @throws Exception\MatrixException
         * @throws Exception\IncorrectTypeException
         */
        public function pi(float $x, float $p, int $q = 1): float
        {
            $V = $this->regressionVariance($x) + 1 / $q;
            $σ² = $this->meanSquareResidual();

            // The t-value
            $studentT = new StudentT($this->ν);
            $t = $studentT->inverse2Tails($p);

            return $t * sqrt($σ² * $V);
        }
    }
