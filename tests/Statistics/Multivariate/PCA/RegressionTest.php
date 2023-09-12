<?php

    namespace MathPHP\Tests\Statistics\Multivariate\PCA;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\Functions\Map\Multi;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\Statistics\Multivariate\PCA;
    use PHPUnit\Framework\TestCase;

    /**
     * Regression test for bug issue 414
     *
     * @link https://github.com/markrogoyski/math-php/issues/414
     *
     * The issue was construction the PCA with highly correlated data, calculating the eigenvalues would not converge,
     * resulting in an infinite loop.
     */
    class RegressionTest extends TestCase {
        /**
         * @test PCA center false and scale false
         *
         * R test data
         * > library(mdatools)
         * >
         * > data = rbind(c(0.066073, 96.000000), c(5.407780, 1115.857143), c(19.440563, 3999.142857), c(35.582583, 7315.857143), c(71.602260, 14725.428571), c(165.725077, 34186.000000), c(235.426483, 48657.857143), c(256.868816, 53160.316186))
         * > data
         * [,1]      [,2]
         * [1,]   0.066073    96.000
         * [2,]   5.407780  1115.857
         * [3,]  19.440563  3999.143
         * [4,]  35.582583  7315.857
         * [5,]  71.602260 14725.429
         * [6,] 165.725077 34186.000
         * [7,] 235.426483 48657.857
         * [8,] 256.868816 53160.316
         * >
         * > model = pca(data, center=FALSE, scale=FALSE)
         *
         * > loadings = model$loadings
         * > loadings
         * Comp 1       Comp 2
         * [1,] -0.004838294 -0.999988295
         * [2,] -0.999988295  0.004838294
         */
        public function testBugCenterFalseScaleFalseLoadings()
        {
            // Given
            try
            {
                $data = MatrixFactory::createNumeric([
                    [0.066073, 96.000000],
                    [5.407780, 1115.857143],
                    [19.440563, 3999.142857],
                    [35.582583, 7315.857143],
                    [71.602260, 14725.428571],
                    [165.725077, 34186.000000],
                    [235.426483, 48657.857143],
                    [256.868816, 53160.316186],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // And
            $center = TRUE;
            $scale = FALSE;

            // When
            try
            {
                $model = new PCA($data, TRUE, FALSE);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // Then
            $expected = [
                [-0.004838294, -0.999988295],
                [-0.999988295, 0.004838294],
            ];
            $loadings = $model->getLoadings();

            // And since each column could be multiplied by -1, we will compare the two and adjust.
            // Get an array that's roughly ones and negative ones.
            try
            {
                $quotient = Multi::divide($expected[1],
                    $loadings->getMatrix()[1]);
            } catch (BadDataException $e)
            {
            }

            // Convert to exactly one or negative one. Cannot be zero.
            $array_map = array_map(function ($x) {
                return $x <=> 0;
            }, $quotient);
            $signum = $array_map;
            try
            {
                $sign_change = MatrixFactory::diagonal($signum);
            } catch (MatrixException $e)
            {
            }

            // Multiplying a sign change matrix on the right changes column signs.
            try
            {
                $sign_adjusted = $loadings->multiply($sign_change);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (MathException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected, $sign_adjusted->getMatrix(),
                .00001);
        }
    }
