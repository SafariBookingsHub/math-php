<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use PHPUnit\Framework\TestCase;

    class MatrixFactoryErrorPropagationTest
        extends TestCase {
        private const ε = 0.01;
        private NumericMatrix $A;

        public function setUp(): void
        {
            try
            {
                $this->A = MatrixFactory::createNumeric(A: [
                    [1, 2],
                    [3, 4],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }
            $this->A->setError(ε: self::ε);
        }

        /* **************************** *
         * MatrixFactory::createNumeric
         * **************************** */

        /**
         * @test add propagates error tolerance to resultant matrix
         */
        public function testAdd()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->add(B: $B);
            } catch (IncorrectTypeException|MathException|MatrixException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test directSum propagates error tolerance to resultant matrix
         */
        public function testDirectSum()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            $R = $this->A->directSum(B: $B);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test subtract propagates error tolerance to resultant matrix
         */
        public function testSubtract()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            $R = $this->A->subtract(B: $B);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test multiply propagates error tolerance to resultant matrix
         */
        public function testMultiply()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->multiply(B: $B);
            } catch (IncorrectTypeException|MathException|MatrixException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test scalarMultiply propagates error tolerance to resultant matrix
         */
        public function testScalarMultiply()
        {
            // Given
            $x = 4;

            // When
            $R = $this->A->scalarMultiply(λ: $x);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test scalarDivide propagates error tolerance to resultant matrix
         */
        public function testScalarDivide()
        {
            // Given
            $x = 4;

            // When
            try
            {
                $R = $this->A->scalarDivide(λ: $x);
            } catch (BadParameterException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test hadamardProduct propagates error tolerance to resultant matrix
         */
        public function testHadamardProduct()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            $R = $this->A->hadamardProduct(B: $B);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test diagonal propagates error tolerance to resultant matrix
         */
        public function testDiagonal()
        {
            // When
            $R = $this->A->diagonal();

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test inverse propagates error tolerance to resultant matrix
         */
        public function testInverse()
        {
            // When
            try
            {
                $R = $this->A->inverse();
            } catch (BadParameterException|OutOfBoundsException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test cofactorMatrix propagates error tolerance to resultant matrix
         */
        public function testCofactorMatrix()
        {
            // When
            try
            {
                $R = $this->A->cofactorMatrix();
            } catch (BadParameterException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowMultiply propagates error tolerance to resultant matrix
         */
        public function testRowMultiply()
        {
            // Given
            $m = 1;
            $k = 2;

            // When
            $R = $this->A->rowMultiply(mᵢ: $m, k: $k);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowDivide propagates error tolerance to resultant matrix
         */
        public function testRowDivide()
        {
            // Given
            $m = 1;
            $k = 2;

            // When
            try
            {
                $R = $this->A->rowDivide(mᵢ: $m, k: $k);
            } catch (BadParameterException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowAdd propagates error tolerance to resultant matrix
         */
        public function testRowAdd()
        {
            // Given
            $i = 1;
            $j = 1;
            $k = 2;

            // When
            try
            {
                $R = $this->A->rowAdd(mᵢ: $i, mⱼ: $j, k: $k);
            } catch (BadParameterException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowAddScalar propagates error tolerance to resultant matrix
         */
        public function testRowAddScalar()
        {
            // Given
            $m = 1;
            $k = 2;

            // When
            $R = $this->A->rowAddScalar(mᵢ: $m, k: $k);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowSubtract propagates error tolerance to resultant matrix
         */
        public function testRowSubtract()
        {
            // Given
            $i = 1;
            $j = 1;
            $k = 2;

            // When
            $R = $this->A->rowSubtract(mᵢ: $i, mⱼ: $j, k: $k);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowSubtractScalar propagates error tolerance to resultant matrix
         */
        public function testRowSubtractScalar()
        {
            // Given
            $m = 1;
            $k = 2;

            // When
            $R = $this->A->rowSubtractScalar(mᵢ: $m, k: $k);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test columnAdd propagates error tolerance to resultant matrix
         */
        public function testColumnAdd()
        {
            // Given
            $i = 1;
            $j = 1;
            $k = 2;

            // When
            try
            {
                $R = $this->A->columnAdd(nᵢ: $i, nⱼ: $j, k: $k);
            } catch (BadParameterException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test columnMultiply propagates error tolerance to resultant matrix
         */
        public function testColumnMultiply()
        {
            // Given
            $n = 1;
            $k = 2;

            // When
            $R = $this->A->columnMultiply(nᵢ: $n, k: $k);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test adjugate propagates error tolerance to resultant matrix
         */
        public function testAdjugate()
        {
            // When
            try
            {
                try
                {
                    $R = $this->A->adjugate();
                } catch (BadDataException $e)
                {
                } catch (BadParameterException $e)
                {
                } catch (IncorrectTypeException $e)
                {
                } catch (MatrixException $e)
                {
                } catch (MathException $e)
                {
                }
            } catch (BadParameterException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /* ********************* *
         * MatrixFactory::create
         * ********************* */

        /**
         * @test augment propagates error tolerance to resultant matrix
         */
        public function testAugment()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            $R = $this->A->augment($B);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test augmentLeft propagates error tolerance to resultant matrix
         */
        public function testAugmentLeft()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            $R = $this->A->augmentLeft($B);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test augmentBelow propagates error tolerance to resultant matrix
         */
        public function testAugmentBelow()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            $R = $this->A->augmentBelow($B);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test augmentAbove propagates error tolerance to resultant matrix
         */
        public function testAugmentAbove()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->augmentAbove($B);
            } catch (BadDataException|MathException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test transpose propagates error tolerance to resultant matrix
         */
        public function testTranspose()
        {
            // When
            $R = $this->A->transpose();

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test submatrix propagates error tolerance to resultant matrix
         */
        public function testSubmatrix()
        {
            // Given
            try
            {
                $B = MatrixFactory::createNumeric(A: [
                    [3],
                ]);
            } catch (BadDataException|MathException $e)
            {
            }
            $m = 1;
            $n = 1;

            // When
            try
            {
                $R = $this->A->insert($B, $m, $n);
            } catch (MatrixException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test map propagates error tolerance to resultant matrix
         */
        public function testMap()
        {
            // Given
            $f = fn($x) => $x + 1;

            // When
            $R = $this->A->map($f);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowInterchange propagates error tolerance to resultant matrix
         */
        public function testRowInterchange()
        {
            // Given
            $i = 0;
            $j = 1;

            // When
            $R = $this->A->rowInterchange($i, $j);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test rowExclude propagates error tolerance to resultant matrix
         */
        public function testRowExclude()
        {
            // Given
            $i = 0;

            // When
            $R = $this->A->rowExclude($i);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test columnInterchange propagates error tolerance to resultant matrix
         */
        public function testcolumnInterchange()
        {
            // Given
            $i = 0;
            $j = 1;

            // When
            $R = $this->A->columnInterchange($i, $j);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test columnExclude propagates error tolerance to resultant matrix
         */
        public function testColumnExclude()
        {
            // Given
            $i = 0;

            // When
            $R = $this->A->columnExclude($i);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test leadingPrincipalMinor propagates error tolerance to resultant matrix
         */
        public function testLeadingPrincipalMinor()
        {
            // Given
            $k = 1;

            // When
            $R = $this->A->leadingPrincipalMinor($k);

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /* ******************************** *
         * MatrixFactory::createFromVectors
         * ******************************** */

        /**
         * @test meanDeviationOfRowVariables propagates error tolerance to resultant matrix
         */
        public function testMeanDeviationOfRowVariables()
        {
            // When
            $R = $this->A->meanDeviationOfRowVariables();

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test meanDeviationOfColumnVariables propagates error tolerance to resultant matrix
         */
        public function testMeanDeviationOfColumnVariables()
        {
            // When
            $R = $this->A->meanDeviationOfColumnVariables();

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }
    }
