<?php

    namespace MathPHP\Number;

    use JetBrains\PhpStorm\Pure;
    use MathPHP\Exception;

    use function abs;
    use function is_numeric;
    use function print_r;

    /**
     * Quaternionic Numbers
     *
     * A quaternion is a number that can be expressed in the form a + bi + cj + dk,
     * where a, b, c, andd are real numbers and i, j, and k are the basic quaternions, satisfying the
     * equation i² = j² = k² = ijk = −1.
     * https://en.wikipedia.org/wiki/Quaternion
     */
    class Quaternion implements ObjectArithmetic {
        /** Floating-point range near zero to consider insignificant */
        const EPSILON = 1e-6;
        /** @var int|float Real part of the quaternionic number */
        protected string|int|float $r;
        /** @var int|float First Imaginary part of the quaternionic number */
        protected string|int|float $i;
        /** @var int|float Second Imaginary part of the quaternionic number */
        protected string|int|float $j;
        /** @var int|float Third Imaginary part of the quaternionic number */
        protected string|int|float $k;

        /**
         * @param float|int $r Real part
         * @param float|int $i Imaginary part
         * @param float|int $j Imaginary part
         * @param float|int $k Imaginary part
         *
         * @throws \MathPHP\Exception\BadDataException
         */
        public function __construct(float|int $r, float|int $i, float|int $j, float|int $k)
        {
            if ( ! is_numeric($r) || ! is_numeric($i) || ! is_numeric($j)
                || ! is_numeric($k)
            )
                throw new Exception\BadDataException('Values must be real numbers.');
            $this->r = $r;
            $this->i = $i;
            $this->j = $j;
            $this->k = $k;
        }

        /**
         * Creates 0 + 0i
         *
         * @return Quaternion
         */
        public static function createZeroValue(): ObjectArithmetic
        {
            try
            {
                return new Quaternion(0, 0, 0, 0);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * String representation of a complex number
         * a + bi + cj + dk, a - bi - cj - dk, etc.
         *
         * @return string
         */
        #[Pure] public function __toString(): string
        {
            if ($this->r == 0 & $this->i == 0 & $this->j == 0 & $this->k
                    == 0
            )
                return '0';
            $string = self::stringifyNumberPart($this->r);
            $string = self::stringifyNumberPart($this->i, 'i', $string);
            $string = self::stringifyNumberPart($this->j, 'j', $string);

            return self::stringifyNumberPart($this->k, 'k', $string);
        }

        /**
         * Stringify an additional part of the quaternion
         *
         * @param float|int $q
         * @param string    $unit
         * @param string    $string
         *
         * @return string
         */
        private static function stringifyNumberPart(
            float|int $q,
            string $unit = '',
            string $string = ''
        ): string {
            if ($q == 0)
                return $string;
            if ($q > 0)
            {
                $plus = $string == '' ? '' : ' + ';

                return $string.$plus."$q".$unit;
            }
            $minus = $string == '' ? '-' : ' - ';

            return $string.$minus.abs($q).$unit;
        }

        /**************************************************************************
         * UNARY FUNCTIONS
         **************************************************************************/

        /**
         * Get r or i or j or k
         *
         * @param string $part
         *
         * @return int|float
         *
         * @throws Exception\BadParameterException if something other than r or i is attempted
         */
        public function __get(string $part)
        {
            return match ($part)
            {
                'r', 'i', 'j', 'k' => $this->$part,
                default => throw new Exception\BadParameterException("The $part property does not exist in Quaternion"),
            };
        }

        /**
         * The inverse of a quaternion (reciprocal)
         *
         * https://en.wikipedia.org/wiki/Quaternion#Conjugation.2C_the_norm.2C_and_reciprocal
         *
         *                                1
         * (a + bi + cj + dk)⁻¹ = ----------------- (a - bi - cj -dk)
         *                        a² + b² + c² + d²
         *
         * @return Quaternion
         *
         * @throws \MathPHP\Exception\BadDataException if = to 0 + 0i
         */
        public function inverse(): Quaternion
        {
            if ($this->r == 0 && $this->i == 0 && $this->j == 0
                && $this->k == 0
            )
                throw new Exception\BadDataException('Cannot take inverse of 0 + 0i');

            try
            {
                return $this->complexConjugate()->divide($this->abs() ** 2);
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * Quaternion division
         * Dividing two quaternions is accomplished by multiplying the first by the inverse of the second
         * This is not commutative!
         *
         * @param float|int|Quaternion $q
         *
         * @return Quaternion
         *
         * @throws \MathPHP\Exception\IncorrectTypeException if the argument is not numeric or Complex.
         */
        public function divide(Quaternion|float|int $q): Quaternion
        {
            if ( ! is_numeric($q) && ! $q instanceof Quaternion)
                throw new Exception\IncorrectTypeException('Argument must be real or quaternion'
                    .print_r($q, TRUE));

            if (is_numeric($q))
            {
                $r = $this->r / $q;
                $i = $this->i / $q;
                $j = $this->j / $q;
                $k = $this->k / $q;

                try
                {
                    return new Quaternion($r, $i, $j, $k);
                } catch (Exception\BadDataException $e)
                {
                }
            }

            try
            {
                return $this->multiply($q->inverse());
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * Quaternion multiplication (Hamilton product)
         *
         * (a₁ + b₁i - c₁j - d₁k)(a₂ + b₂i + c₂j + d₂k)
         *
         *      a₁a₂ - b₁b₂ - c₁c₂ - d₁d₂
         *   + (b₁a₂ + a₁b₂ + c₁d₂ - d₁c₂)i
         *   + (a₁c₂ - b₁d₂ + c₁a₂ + d₁b₂)k
         *   + (a₁d₂ + b₁c₂ - c₁b₂ + d₁a₂)k
         *
         * Note: Quaternion multiplication is not commutative.
         *
         * @param mixed $object_or_scalar
         *
         * @return Quaternion
         *
         * @throws \MathPHP\Exception\BadDataException
         * @throws \MathPHP\Exception\IncorrectTypeException if the argument is not numeric or Complex.
         */
        public function multiply(mixed $object_or_scalar): Quaternion
        {
            if ( ! is_numeric($object_or_scalar) && ! $object_or_scalar instanceof Quaternion)
                throw new Exception\IncorrectTypeException('Argument must be real or quaternion'
                    .print_r($object_or_scalar, TRUE));
            if (is_numeric($object_or_scalar))
                return new Quaternion($this->r * $object_or_scalar, $this->i * $object_or_scalar,
                    $this->j * $object_or_scalar, $this->k * $object_or_scalar);

            [$a₁, $b₁, $c₁, $d₁] = [$this->r, $this->i, $this->j, $this->k];
            [$a₂, $b₂, $c₂, $d₂] = [$object_or_scalar->r, $object_or_scalar->i, $object_or_scalar->j, $object_or_scalar->k];

            try
            {
                return new Quaternion(
                    $a₁ * $a₂ - $b₁ * $b₂ - $c₁ * $c₂ - $d₁ * $d₂,
                    ($b₁ * $a₂) + ($a₁ * $b₂) + ($c₁ * $d₂) - $d₁ * $c₂,
                    ($a₁ * $c₂) - ($b₁ * $d₂) + $c₁ * $a₂ + $d₁ * $b₂,
                    ($a₁ * $d₂ + $b₁ * $c₂) - ($c₁ * $b₂) + $d₁ * $a₂
                );
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**************************************************************************
         * BINARY FUNCTIONS
         **************************************************************************/

        /**
         * The conjugate of a quaternion
         * https://en.wikipedia.org/wiki/Quaternion#Conjugation.2C_the_norm.2C_and_reciprocal
         *
         * q* = a - bi - cj -dk
         *
         * @return Quaternion
         */
        public function complexConjugate(): Quaternion
        {
            try
            {
                return new Quaternion($this->r, -$this->i, -$this->j,
                    -$this->k);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * The absolute value (magnitude) of a quaternion or norm
         * https://en.wikipedia.org/wiki/Quaternion#Conjugation.2C_the_norm.2C_and_reciprocal
         *
         * If z = a + bi + cj + dk
         *        _________________
         * |z| = √a² + b² + c² + d²
         *
         * @return float
         */
        public function abs(): float|int
        {
            return sqrt($this->r ** 2 + $this->i ** 2 + $this->j ** 2
                + $this->k
                    ** 2);
        }

        /**
         * Negate the quaternion
         * Switches the signs of both the real and imaginary parts.
         *
         * @return Quaternion
         */
        public function negate(): Quaternion
        {
            try
            {
                return new Quaternion(-$this->r, -$this->i, -$this->j,
                    -$this->k);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * Quaternion addition
         *
         *
         * (a + bi + cj + dk) - (e + fi + gj + hk) = (a + e) + (b + f)i + (c + g)j + (d + h)k
         *
         * @param mixed $object_or_scalar
         *
         * @return Quaternion
         *
         * @throws \MathPHP\Exception\IncorrectTypeException if the argument is not numeric or Complex.
         */
        public function add(mixed $object_or_scalar): Quaternion
        {
            if ( ! is_numeric($object_or_scalar) && ! $object_or_scalar instanceof Quaternion)
                throw new Exception\IncorrectTypeException('Argument must be real or quaternion'
                    .print_r($object_or_scalar, TRUE));
            if (is_numeric($object_or_scalar))
            {
                $r = $this->r + $object_or_scalar;

                try
                {
                    return new Quaternion($r, $this->i, $this->j, $this->k);
                } catch (Exception\BadDataException $e)
                {
                }
            }

            $r = $this->r + $object_or_scalar->r;
            $i = $this->i + $object_or_scalar->i;
            $j = $this->j + $object_or_scalar->j;
            $k = $this->k + $object_or_scalar->k;

            try
            {
                return new Quaternion($r, $i, $j, $k);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**************************************************************************
         * COMPARISON FUNCTIONS
         **************************************************************************/

        /**
         * Quaternion subtraction
         *
         *
         * (a + bi + cj + dk) - (e + fi + gj + hk) = (a - e) + (b - f)i + (c - g)j + (d - h)k
         *
         * @param mixed $object_or_scalar
         *
         * @return Quaternion
         *
         * @throws \MathPHP\Exception\IncorrectTypeException if the argument is not numeric or Complex.
         */
        public function subtract(mixed $object_or_scalar): Quaternion
        {
            if ( ! is_numeric($object_or_scalar) && ! $object_or_scalar instanceof Quaternion)
                throw new Exception\IncorrectTypeException('Argument must be real or quaternion'
                    .print_r($object_or_scalar, TRUE));
            if (is_numeric($object_or_scalar))
            {
                $r = $this->r - $object_or_scalar;

                try
                {
                    return new Quaternion($r, $this->i, $this->j, $this->k);
                } catch (Exception\BadDataException $e)
                {
                }
            }

            $r = $this->r - $object_or_scalar->r;
            $i = $this->i - $object_or_scalar->i;
            $j = $this->j - $object_or_scalar->j;
            $k = $this->k - $object_or_scalar->k;

            try
            {
                return new Quaternion($r, $i, $j, $k);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**************************************************************************
         * PRIVATE FUNCTIONS
         **************************************************************************/

        /**
         * Test for equality
         * Two quaternions are equal if and only if both their real and imaginary parts are equal.
         *
         *
         * @param Quaternion $q
         *
         * @return bool
         */
        public function equals(Quaternion $q): bool
        {
            return abs($this->r - $q->r) < self::EPSILON
                && abs($this->i - $q->i) < self::EPSILON
                && abs($this->j - $q->j) < self::EPSILON
                && abs($this->k - $q->k) < self::EPSILON;
        }

        public function quaternionDivideException()
        {
        }

        public function quaternionSubtractException()
        {
        }

        public function quaternionAddException()
        {
        }

        public function subtractReal()
        {
        }

        public function addReal()
        {
        }

        public function inverseException()
        {
        }

        public function constructorException()
        {
        }

        public function getException()
        {
        }

        public function get()
        {
        }

        public function toString()
        {
        }

        public function zeroValue()
        {
        }

        public function objectArithmeticInterface()
        {
        }
    }
