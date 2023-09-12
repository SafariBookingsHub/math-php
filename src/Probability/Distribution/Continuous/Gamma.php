<?php

    namespace MathPHP\Probability\Distribution\Continuous;

    use JetBrains\PhpStorm\Pure;
    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\Functions\Special;
    use MathPHP\Functions\Support;

    use const M_E;
    use const NAN;

    /**
     * Gamma distribution
     * https://en.wikipedia.org/wiki/Gamma_distribution
     */
    class Gamma extends Continuous {
        /**
         * Distribution parameter bounds limits
         * k ∈ (0,∞)
         * θ ∈ (0,∞)
         *
         * @var array{"k": string, "θ": string}
         */
        public const PARAMETER_LIMITS
            = [
                'k' => '(0,∞)',
                'θ' => '(0,∞)',
            ];

        /**
         * Distribution suport bounds limits
         * x ∈ (0,∞)
         *
         * @var array{x: string}
         */
        public final const SUPPORT_LIMITS
            = [
                'x' => '(0,∞)',
            ];

        /** @var float shape parameter k > 0 */
        protected float $k;

        /** @var float shape parameter θ > 0 */
        protected float $θ;

        /**
         * Constructor
         *
         * @param float $k shape parameter k > 0
         * @param float $θ scale parameter θ > 0
         */
        public function __construct(float $k, float $θ)
        {
            parent::__construct($k, $θ);
        }

        public static function modeNan()
        {
        }

        /**
         * Probability density function
         *
         *                     ₓ
         *          1         ⁻-
         * pdf = ------ xᵏ⁻¹ e θ
         *       Γ(k)θᵏ
         *
         * @param float $x percentile      x > 0
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

            $k = $this->k;
            $θ = $this->θ;

            $Γ⟮k⟯ = Special::Γ($k);
            $θᵏ = $θ ** $k;
            $Γ⟮k⟯θᵏ = $Γ⟮k⟯ * $θᵏ;

            $xᵏ⁻¹ = $x ** ($k - 1);
            $e = M_E ** (-$x / $θ);

            return ($xᵏ⁻¹ * $e) / $Γ⟮k⟯θᵏ;
        }

        /**
         * Cumulative distribution function
         *
         *         1      /   x \
         * cdf = ----- γ | k, -  |
         *       Γ(k)     \   θ /
         *
         * @param float $x percentile      x > 0
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

            $k = $this->k;
            $θ = $this->θ;

            $Γ⟮k⟯ = Special::Γ($k);
            $γ = Special::γ($k, $x / $θ);

            return $γ / $Γ⟮k⟯;
        }

        /**
         * Approximation of the median of the distribution
         * https://en.wikipedia.org/wiki/Gamma_distribution#Median_calculation
         *
         *       3k - 0.8
         * υ ≈ μ --------
         *       3k + 0.2
         *
         * @return float
         */
        #[Pure] public function median(): float
        {
            $μ = $this->mean();
            $３k = 3 * $this->k;

            return $μ * (($３k - 0.8) / ($３k + 0.2));
        }

        /**
         * Mean of the distribution
         *
         * μ = k θ
         *
         * @return float
         */
        public function mean(): float
        {
            return $this->k * $this->θ;
        }

        /**
         * Mode of the distribution
         *
         * mode = (k - 1)θ   k ≥ 1
         *
         * @return float
         */
        public function mode(): float
        {
            if ($this->k < 1)
            {
                {
                    return NAN;
                }
            }

            return ($this->k - 1) * $this->θ;
        }

        /**
         * Variance of the distribution
         *
         * var[X] = kθ²
         *
         * @return float
         */
        public function variance(): float
        {
            return $this->k * $this->θ ** 2;
        }
    }
