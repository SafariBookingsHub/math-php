<?php

    namespace MathPHP\Probability\Distribution\Continuous;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\NanException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\Functions\Special;
    use MathPHP\Functions\Support;

    use function exp;
    use function log;

    class Weibull extends Continuous {
        /**
         * Distribution parameter bounds limits
         * λ ∈ (0,∞)
         * k ∈ (0,∞)
         *
         * @var array{"k": string, "λ": string}
         */
        public const PARAMETER_LIMITS
            = [
                'k' => '(0,∞)',
                'λ' => '(0,∞)',
            ];

        /**
         * Distribution support bounds limits
         * x ∈ [0,∞)
         *
         * @var array{x: string}
         */
        public final const SUPPORT_LIMITS
            = [
                'x' => '(-∞,∞)',
            ];

        /** @var float Shape Parameter */
        protected float $k;

        /** @var float Scale Parameter */
        protected float $λ;

        /**
         * Constructor
         *
         * @param float $k shape parameter k > 0
         * @param float $λ scale parameter λ > 0
         */
        public function __construct(float $k, float $λ)
        {
            parent::__construct($k, $λ);
        }

        public static function inverseOfCdf()
        {
        }

        /**
         * Weibull distribution - probability density function
         *
         * https://en.wikipedia.org/wiki/Weibull_distribution
         *
         *        k  /x\ ᵏ⁻¹        ᵏ
         * f(x) = - | - |    ℯ⁻⁽x/λ⁾   for x ≥ 0
         *        λ  \λ/
         *
         * f(x) = 0                    for x < 0
         *
         * @param float $x percentile (value to evaluate)
         *
         * @return float
         */
        public function pdf(float $x): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
            if ($x < 0)
            {
                return 0;
            }

            $k = $this->k;
            $λ = $this->λ;

            $k／λ = $k / $λ;
            $⟮x／λ⟯ᵏ⁻¹ = ($x / $λ) ** ($k - 1);
            $ℯ⁻⁽x／λ⁾ᵏ = exp(-(($x / $λ) ** $k));

            return $k／λ * $⟮x／λ⟯ᵏ⁻¹ * $ℯ⁻⁽x／λ⁾ᵏ;
        }

        /**
         * Weibull distribution - cumulative distribution function
         * From 0 to x (lower CDF)
         * https://en.wikipedia.org/wiki/Weibull_distribution
         *
         * f(x) = 1 - ℯ⁻⁽x/λ⁾ for x ≥ 0
         * f(x) = 0           for x < 0
         *
         * @param float $x percentile (value to evaluate)
         *
         * @return float
         */
        public function cdf(float $x): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
            if ($x < 0)
            {
                return 0;
            }

            $k = $this->k;
            $λ = $this->λ;

            $ℯ⁻⁽x／λ⁾ᵏ = exp(-(($x / $λ) ** $k));

            return 1 - $ℯ⁻⁽x／λ⁾ᵏ;
        }

        /**
         * Inverse CDF (Quantile function)
         *
         * Q(p;k,λ) = λ(-ln(1 - p))¹/ᵏ
         *
         * @param float $p
         *
         * @return float
         */
        public function inverse(float $p): float
        {
            try
            {
                Support::checkLimits(['p' => '[0,1]'], ['p' => $p]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
            $k = $this->k;
            $λ = $this->λ;

            return $λ * (-1 * log(1 - $p)) ** (1 / $k);
        }

        /**
         * Mean of the distribution
         *
         * μ = λΓ(1 + 1/k)
         *
         * @return float
         */
        public function mean(): float
        {
            $k = $this->k;
            $λ = $this->λ;

            try
            {
                return $λ * Special::gamma(1 + (1 / $k));
            } catch (NanException|OutOfBoundsException $e)
            {
            }
        }

        /**
         * Median of the distribution
         *
         * median = λ(ln 2)¹ᐟᵏ
         *
         * @return float
         */
        public function median(): float
        {
            $k = $this->k;
            $λ = $this->λ;

            $⟮ln 2⟯¹ᐟᵏ = log(2) ** (1 / $k);

            return $λ * $⟮ln 2⟯¹ᐟᵏ;
        }

        /**
         * Mode of the distribution
         *
         *    / k - 1  \¹ᐟᵏ
         * λ |  -----  |
         *    \   k    /
         *
         * 0  k ≤ 1
         *
         * @return float
         */
        public function mode(): float
        {
            $k = $this->k;
            $λ = $this->λ;

            if ($k <= 1)
            {
                return 0;
            }

            $⟮⟮k − 1⟯／k⟯¹ᐟᵏ = (($k - 1) / $k) ** (1 / $k);

            return $λ * $⟮⟮k − 1⟯／k⟯¹ᐟᵏ;
        }

        public function rand(): float|int
        {
        }
    }
