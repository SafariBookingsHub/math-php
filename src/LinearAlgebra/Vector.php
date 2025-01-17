<?php

    namespace MathPHP\LinearAlgebra;

    use ArrayAccess;
    use Countable;
    use Iterator;
    use JetBrains\PhpStorm\Pure;
    use JsonSerializable;
    use MathPHP\Exception;
    use MathPHP\Functions\Map;
    use MathPHP\Statistics\Distance;
    use ReturnTypeWillChange;

    use function acos;
    use function array_map;
    use function array_sum;
    use function count;
    use function implode;
    use function max;
    use function min;
    use function rad2deg;
    use function sqrt;

    /**
     * 1 x n Vector
     *
     * @implements \Iterator<int|float>
     * @implements \ArrayAccess<int, int|float>
     */
    class Vector
        implements Countable, Iterator, ArrayAccess, JsonSerializable {
        /** @var int Number of elements */
        private int $n;

        /** @var int Iterator position */
        private int $i;

        /**
         * Constructor
         *
         * @param array<int|float> $A 1 x n vector
         *
         * @throws Exception\BadDataException if the Vector is empty
         */
        public function __construct(private array $A)
        {
            $this->n = count($A);
            $this->i = 0;

            if ($this->n === 0)
            {
                throw new Exception\BadDataException('Vector cannot be empty');
            }
        }

        /**************************************************************************
         * BASIC VECTOR GETTERS
         *  - getVector
         *  - getN
         *  - get
         *  - asColumnMatrix
         *  - asRowMatrix
         **************************************************************************/

        public static function emptyVectorException()
        {
        }

        public static function toString()
        {
        }

        public static function getException()
        {
        }

        /**
         * Get a specific value at position i
         *
         * @param int $i index
         *
         * @return int|float
         *
         * @throws Exception\VectorException
         */
        public function get(int $i): float|int
        {
            if ($i >= $this->n)
            {
                throw new Exception\VectorException("Element $i does not exist");
            }

            return $this->A[$i];
        }

        /**
         * Sum of all elements
         *
         * @return float|int
         */
        public function sum(): float|int
        {
            return array_sum($this->A);
        }

        /**************************************************************************
         * VECTOR NUMERIC OPERATIONS - Return a number
         *  - sum
         *  - length (magnitude)
         *  - max
         *  - min
         *  - dotProduct (innerProduct)
         *  - perpDotProduct
         *  - angleBetween
         *  - l1Distance
         *  - l2Distance
         *  - minkowskiDistance
         **************************************************************************/

        /**
         * Vector length (magnitude)
         * Same as l2-norm
         *
         * @return float|int
         */
        #[Pure] public function length(): float|int
        {
            return $this->l2Norm();
        }

        /**
         * l²-norm (|x|₂)
         * Also known as Euclidean norm, Euclidean length, L² distance, ℓ² distance
         * Used to normalize a vector.
         *
         * http://mathworld.wolfram.com/L2-Norm.html
         * https://en.wikipedia.org/wiki/Norm_(mathematics)#Euclidean_norm
         *         ______
         * |x|₂ = √∑|xᵢ|²
         *
         * @return float
         */
        public function l2Norm(): float
        {
            return sqrt(array_sum(Map\Single::square($this->A)));
        }

        /**
         * Max of all the elements
         *
         * @return int|float|false
         *
         * Note: Remove false from return value after PHP 8.0
         */
        public function max(): float|bool|int
        {
            return max($this->A);
        }

        /**
         * Min of all the elements
         *
         * @return int|float|false
         *
         * Note: Remove false from return value after PHP 8.0
         */
        public function min(): float|bool|int
        {
            return min($this->A);
        }

        /**
         * Inner product (convience method for dot product) (A⋅B)
         *
         * @param Vector $B
         *
         * @return float|int
         */
        public function innerProduct(Vector $B): float|int
        {
            try
            {
                return $this->dotProduct($B);
            } catch (Exception\VectorException $e)
            {
            }
        }

        /**
         * Dot product (inner product) (A⋅B)
         * https://en.wikipedia.org/wiki/Dot_product
         *
         * @param Vector $B
         *
         * @return float|int
         *
         * @throws Exception\VectorException
         */
        public function dotProduct(Vector $B): float|int
        {
            if ($B->getN() !== $this->n)
            {
                throw new Exception\VectorException('Vectors have different number of items');
            }

            return array_sum(array_map(
                fn($a, $b) => $a * $b,
                $this->A,
                $B->getVector()
            ));
        }

        /**
         * Get item count (n)
         *
         * @return int number of items
         */
        public function getN(): int
        {
            return $this->n;
        }

        /**
         * Get matrix
         *
         * @return array<int|float>
         */
        public function getVector(): array
        {
            return $this->A;
        }

        /**
         * Angle between two vectors (cosine similarity)
         *
         *           A⋅B
         * cos α = -------
         *         |A|⋅|B|
         *
         * @param Vector $B
         * @param bool   $inDegrees Determines whether the angle should be returned in degrees or in radians
         *
         * @return float The angle between the vectors in radians (or degrees if specified)
         *
         * @throws Exception\BadDataException
         * @throws Exception\VectorException
         */
        public function angleBetween(Vector $B, bool $inDegrees = FALSE): float
        {
            $cos⟮α⟯ = Distance::cosineSimilarity($this->getVector(),
                $B->getVector());
            $angle = acos($cos⟮α⟯);

            return $inDegrees
                ? rad2deg($angle)
                : $angle;
        }

        /**
         * L1 distance
         * Calculates the taxicap geometry (sometimes Manhatten distance) between the vectors
         * https://en.wikipedia.org/wiki/Taxicab_geometry
         *
         * @param Vector $B
         *
         * @return float
         *
         * @throws Exception\BadDataException
         */
        public function l1Distance(Vector $B): float
        {
            return Distance::manhattan($this->getVector(), $B->getVector());
        }

        /**
         * L2 distance
         * Calculates the euclidean distance between the vectors
         * https://en.wikipedia.org/wiki/Euclidean_distance
         *
         * @param Vector $B
         *
         * @return float The euclidean distance between the vectors
         *
         * @throws Exception\BadDataException
         */
        public function l2Distance(Vector $B): float
        {
            return Distance::euclidean($this->getVector(), $B->getVector());
        }

        /**************************************************************************
         * VECTOR OPERATIONS - Return a Vector or Matrix
         *  - add
         *  - subtract
         *  - multiply
         *  - divide
         *  - scalarMultiply
         **************************************************************************/

        /**
         * Calculates the minkowski distance between vectors
         * https://en.wikipedia.org/wiki/Minkowski_distance
         *
         * (Σ|xᵢ - yᵢ|ᵖ)¹/ᵖ
         *
         * @param Vector $B
         * @param int    $p
         *
         * @return float
         *
         * @throws Exception\BadDataException
         */
        public function minkowskiDistance(Vector $B, int $p): float
        {
            return Distance::minkowski($this->getVector(), $B->getVector(), $p);
        }

        /**
         * Add (A + B)
         *
         * A = [a₁, a₂, a₃]
         * B = [b₁, b₂, b₃]
         * A + B = [a₁ + b₁, a₂ + b₂, a₃ + b₃]
         *
         * @param Vector $B
         *
         * @return Vector
         *
         * @throws Exception\VectorException
         * @throws Exception\BadDataException
         */
        public function add(Vector $B): Vector
        {
            if ($B->getN() !== $this->n)
            {
                throw new Exception\VectorException('Vectors must be the same length for addition');
            }

            $R = Map\Multi::add($this->A, $B->getVector());

            return new Vector($R);
        }

        /**
         * Subtract (A - B)
         *
         * A = [a₁, a₂, a₃]
         * B = [b₁, b₂, b₃]
         * A - B = [a₁ - b₁, a₂ - b₂, a₃ - b₃]
         *
         * @param Vector $B
         *
         * @return Vector
         *
         * @throws \MathPHP\Exception\VectorException
         */
        public function subtract(Vector $B): Vector
        {
            if ($B->getN() !== $this->n)
            {
                throw new Exception\VectorException('Vectors must be the same length for subtraction');
            }

            try
            {
                $R = Map\Multi::subtract($this->A, $B->getVector());
            } catch (Exception\BadDataException $e)
            {
            }

            try
            {
                return new Vector($R);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * Outer product (A⨂B)
         * https://en.wikipedia.org/wiki/Outer_product
         * Same as direct product.
         *
         * @param Vector $B
         *
         * @return \MathPHP\LinearAlgebra\Matrix|\MathPHP\LinearAlgebra\NumericMatrix
         */
        public function outerProduct(Vector $B)
        {
            $m = $this->n;
            $n = $B->getN();
            $R = [];

            for ($i = 0; $i < $m; $i++)
            {
                for ($j = 0; $j < $n; $j++)
                {
                    $R[$i][$j] = $this->A[$i] * $B[$j];
                }
            }

            /** @var NumericMatrix $R */
            try
            {
                return MatrixFactory::create($R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * Direct product (dyadic)
         * https://en.wikipedia.org/wiki/Direct_product
         * http://mathworld.wolfram.com/VectorDirectProduct.html
         *
         *              [A₁]              [A₁B₁ A₁B₂ A₁B₃]
         * AB = A⨂Bᵀ = [A₂] [B₁ B₂ B₃] = [A₂B₁ A₂B₂ A₂B₃]
         *              [A₃]              [A₃B₁ A₃B₂ A₃B₃]
         *
         * Where ⨂ is the Kronecker product.
         *
         * @param Vector $B
         *
         * @return NumericMatrix
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MatrixException
         */
        public function directProduct(Vector $B): NumericMatrix
        {
            try
            {
                $A = $this->asColumnMatrix();
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $Bᵀ = $B->asRowMatrix();
            } catch (Exception\MathException $e)
            {
            }

            try
            {
                return $A->kroneckerProduct($Bᵀ);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * Get the vector as an nx1 column matrix
         *
         * Example:
         *  V = [1, 2, 3]
         *
         *      [1]
         *  R = [2]
         *      [3]
         *
         * @return NumericMatrix
         *
         * @throws Exception\MathException
         */
        public function asColumnMatrix(): NumericMatrix
        {
            $array_map1 = [];
            foreach ($this->A as $key => $element)
            {
                $array_map1[$key] = [$element];
            }
            $array_map = $array_map1;
            $matrix = $array_map;

            return new NumericMatrix($matrix);
        }

        /**************************************************************************
         * VECTOR ADVANCED OPERATIONS - Return a Vector or Matrix
         *  - outerProduct
         *  - directProduct (dyadic)
         *  - crossProduct
         *  - normalize
         *  - perpendicular
         *  - projection
         *  - kroneckerProduct
         **************************************************************************/

        /**
         * Get the vector as a 1xn row matrix
         *
         * Example:
         *  V = [1, 2, 3]
         *  R = [
         *   [1, 2, 3]
         *  ]
         *
         * @return NumericMatrix
         *
         * @throws Exception\MathException
         */
        public function asRowMatrix(): NumericMatrix
        {
            return new NumericMatrix([$this->A]);
        }

        /**
         * Kronecker product A⨂B
         * The kronecker product of two column vectors is a column vector.
         *
         * Example:  [1]    [3]   [3]
         *           [2] ⨂ [4] = [4]
         *                        [6]
         *                        [8]
         *
         * @param Vector $B
         *
         * @return Vector
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MatrixException
         */
        public function kroneckerProduct(Vector $B): Vector
        {
            try
            {
                $A = $this->asColumnMatrix();
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $B = $B->asColumnMatrix();
            } catch (Exception\MathException $e)
            {
            }

            try
            {
                $A⨂B = $A->kroneckerProduct($B);
            } catch (Exception\BadDataException $e)
            {
            }

            try
            {
                return new Vector($A⨂B->getColumn(0));
            } catch (Exception\BadDataException|Exception\MatrixException $e)
            {
            }
        }

        /**
         * Cross product (AxB)
         * https://en.wikipedia.org/wiki/Cross_product
         *
         *         | i  j  k  |
         * A x B = | a₀ a₁ a₂ | = |a₁ a₂|  - |a₀ a₂|  + |a₀ a₁|
         *         | b₀ b₁ b₂ |   |b₁ b₂|i   |b₀ b₂|j   |b₀ b₁|k
         *
         *       = (a₁b₂ - b₁a₂) - (a₀b₂ - b₀a₂) + (a₀b₁ - b₀a₁)
         *
         * @param Vector $B
         *
         * @return Vector
         *
         * @throws \MathPHP\Exception\VectorException
         */
        public function crossProduct(Vector $B): Vector
        {
            if (($B->getN() !== 3) || ($this->n !== 3))
            {
                throw new Exception\VectorException('Vectors must have 3 items');
            }

            $s1 = ($this->A[1] * $B[2]) - ($this->A[2] * $B[1]);
            $s2 = -(($this->A[0] * $B[2]) - ($this->A[2] * $B[0]));
            $s3 = ($this->A[0] * $B[1]) - ($this->A[1] * $B[0]);

            try
            {
                return new Vector([$s1, $s2, $s3]);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * Normalize (Â)
         * The normalized vector Â is a vector in the same direction of A
         * but with a norm (length) of 1. It is a unit vector.
         * http://mathworld.wolfram.com/NormalizedVector.html
         *
         *      A
         * Â ≡ ---
         *     |A|
         *
         *  where |A| is the l²-norm (|A|₂)
         *
         * @return Vector
         */
        public function normalize(): Vector
        {
            $│A│ = $this->l2Norm();

            return $this->scalarDivide($│A│);
        }

        /**
         * Scalar divide
         * kA = [k / a₁, k / a₂, k / a₃ ...]
         *
         * @param float|int $k Scale factor
         *
         * @return Vector
         */
        public function scalarDivide(float|int $k): Vector
        {
            try
            {
                return new Vector(Map\Single::divide($this->A, $k));
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * Divide (A / B)
         *
         * A = [a₁, a₂, a₃]
         * B = [b₁, b₂, b₃]
         * A / B = [a₁ / b₁, a₂ / b₂, a₃ / b₃]
         *
         * @param Vector $B
         *
         * @return Vector
         *
         * @throws Exception\VectorException
         * @throws Exception\BadDataException
         */
        public function divide(Vector $B): Vector
        {
            if ($B->getN() !== $this->n)
            {
                throw new Exception\VectorException('Vectors must be the same length for division');
            }

            $R = Map\Multi::divide($this->A, $B->getVector());

            return new Vector($R);
        }

        /**
         * Projection of A onto B
         * https://en.wikipedia.org/wiki/Vector_projection#Vector_projection
         *
         *          A⋅B
         * projᵇA = --- B
         *          |B|²
         *
         * @param Vector $B
         *
         * @return Vector
         */
        public function projection(Vector $B): Vector
        {
            try
            {
                $A⋅B = $this->dotProduct($B);
            } catch (Exception\VectorException $e)
            {
            }
            $│B│² = ($B->l2Norm()) ** 2;

            return $B->scalarMultiply($A⋅B / $│B│²);
        }

        /**
         * Scalar multiplication (scale)
         * kA = [k * a₁, k * a₂, k * a₃ ...]
         *
         * @param float|int $k Scale factor
         *
         * @return Vector
         */
        public function scalarMultiply(float|int $k): Vector
        {
            try
            {
                return new Vector(Map\Single::multiply($this->A, $k));
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**************************************************************************
         * VECTOR NORMS
         *  - l1Norm
         *  - l2Norm
         *  - pNorm
         *  - maxNorm
         **************************************************************************/

        /**
         * Multiply (A * B)
         *
         * A = [a₁, a₂, a₃]
         * B = [b₁, b₂, b₃]
         * A * B = [a₁ * b₁, a₂ * b₂, a₃ * b₃]
         *
         * @param Vector $B
         *
         * @return Vector
         *
         * @throws Exception\VectorException
         * @throws Exception\BadDataException
         */
        public function multiply(Vector $B): Vector
        {
            if ($B->getN() !== $this->n)
            {
                throw new Exception\VectorException('Vectors must be the same length for multiplication');
            }

            $R = Map\Multi::multiply($this->A, $B->getVector());

            return new Vector($R);
        }

        /**
         * Perpendicular of A on B
         * https://en.wikipedia.org/wiki/Vector_projection#Vector_projection
         *
         *          A⋅B⊥
         * perpᵇA = ---- B⊥
         *          |B|²
         *
         * @param Vector $B
         *
         * @return Vector
         */
        public function perp(Vector $B): Vector
        {
            try
            {
                $A⋅B⊥ = $B->perpDotProduct($this);
            } catch (Exception\VectorException $e)
            {
            }
            $│B│² = ($B->l2Norm()) ** 2;
            try
            {
                $B⊥ = $B->perpendicular();
            } catch (Exception\VectorException $e)
            {
            }

            return $B⊥->scalarMultiply($A⋅B⊥ / $│B│²);
        }

        /**
         * Perp dot product (A⊥⋅B)
         * A modification of the two-dimensional dot product in which A is
         * replaced by the perpendicular vector rotated 90º degrees.
         * http://mathworld.wolfram.com/PerpDotProduct.html
         *
         * @param Vector $B
         *
         * @return float|int
         *
         * @throws Exception\VectorException
         */
        public function perpDotProduct(Vector $B): float|int
        {
            if (($this->n !== 2) || ($B->getN() !== 2))
            {
                throw new Exception\VectorException('Cannot do perp dot product unless both vectors are two-dimensional');
            }

            $A⊥ = $this->perpendicular();

            return $A⊥->dotProduct($B);
        }

        /**
         * Perpendicular (A⊥)
         * A vector perpendicular to A (A-perp) with the length that is rotated 90º
         * counter clockwise.
         *
         *     [a]       [-b]
         * A = [b]  A⊥ = [a]
         *
         * @return Vector
         *
         * @throws \MathPHP\Exception\VectorException
         */
        public function perpendicular(): Vector
        {
            if ($this->n !== 2)
            {
                throw new Exception\VectorException('Perpendicular operation only makes sense for 2D vector. 3D and higher vectors have infinite perpendular vectors.');
            }

            $A⊥ = [-$this->A[1], $this->A[0]];

            try
            {
                return new Vector($A⊥);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**************************************************************************
         * PHP MAGIC METHODS
         *  - __toString
         **************************************************************************/

        /**
         * l₁-norm (|x|₁)
         * Also known as Taxicab norm or Manhattan norm
         *
         * https://en.wikipedia.org/wiki/Norm_(mathematics)#Taxicab_norm_or_Manhattan_norm
         *
         * |x|₁ = ∑|xᵢ|
         *
         * @return float|int
         */
        public function l1Norm(): float|int
        {
            return array_sum(Map\Single::abs($this->A));
        }

        /**
         * p-norm (|x|p)
         * Also known as lp norm
         *
         * https://en.wikipedia.org/wiki/Norm_(mathematics)#p-norm
         *
         * |x|p = (∑|xᵢ|ᵖ)¹/ᵖ
         *
         * @param float|int $p
         *
         * @return int|float
         */
        public function pNorm(float|int $p): float|int
        {
            return array_sum(Map\Single::pow(Map\Single::abs($this->A), $p))
                ** (1 / $p);
        }

        /**
         * Max norm (infinity norm) (|x|∞)
         *
         * |x|∞ = max |x|
         *
         * @return int|float|false
         *
         * Note: Remove false from return value after PHP 8.0
         */
        public function maxNorm(): float|bool|int
        {
            return max(Map\Single::abs($this->A));
        }

        /**
         * Print the vector as a string
         * Ex:
         *  [1, 2, 3]
         *
         * @return string
         */
        public function __toString(): string
        {
            return '['.implode(', ', $this->A).']';
        }

        /**************************************************************************
         * Countable INTERFACE
         **************************************************************************/

        public function count(): int
        {
            return count($this->A);
        }

        /**************************************************************************
         * ArrayAccess INTERFACE
         **************************************************************************/

        public function offsetExists(mixed $i): bool
        {
            return isset($this->A[$i]);
        }

        /**************************************************************************
         * JsonSerializable INTERFACE
         **************************************************************************/

        #[ReturnTypeWillChange]
        public function offsetGet(mixed $i): float|int
        {
            return $this->A[$i];
        }

        /**
         * @param int       $i
         * @param int|float $value
         *
         * @throws Exception\VectorException
         */
        public function offsetSet($i, $value): void
        {
            throw new Exception\VectorException('Vector class does not allow setting values');
        }

        /**
         * @param int $i
         *
         * @throws Exception\VectorException
         */
        public function offsetUnset($i): void
        {
            throw new Exception\VectorException('Vector class does not allow unsetting values');
        }

        /**
         * @return array<int|float>
         */
        public function jsonSerialize(): array
        {
            return $this->A;
        }

        /**************************************************************************
         * Iterator INTERFACE
         **************************************************************************/

        public function rewind(): void
        {
            $this->i = 0;
        }

        #[ReturnTypeWillChange]
        public function current(): float|int
        {
            return $this->A[$this->i];
        }

        #[ReturnTypeWillChange]
        public function key(): int
        {
            return $this->i;
        }

        public function next(): void
        {
            ++$this->i;
        }

        public function valid(): bool
        {
            return isset($this->A[$this->i]);
        }
    }
