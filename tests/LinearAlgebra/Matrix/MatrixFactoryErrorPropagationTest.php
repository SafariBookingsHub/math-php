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
        /** @var NumericMatrix */
        private NumericMatrix $A;

        public function setUp(): void
        {
            try
            {
                $this->A = MatrixFactory::createNumeric([
                    [1, 2],
                    [3, 4],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }
            $this->A->setError(self::ε);
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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->add($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (MathException $e)
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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->directSum($B);
            } catch (IncorrectTypeException $e)
            {
            }

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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->subtract($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->multiply($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (MathException $e)
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
            try
            {
                $R = $this->A->scalarMultiply($x);
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            }

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
                $R = $this->A->scalarDivide($x);
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->hadamardProduct($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test diagonal propagates error tolerance to resultant matrix
         */
        public function testDiagonal()
        {
            // When
            try
            {
                $R = $this->A->diagonal();
            } catch (IncorrectTypeException $e)
            {
            }

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
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (OutOfBoundsException $e)
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
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
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
            try
            {
                $R = $this->A->rowMultiply($m, $k);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $R = $this->A->rowDivide($m, $k);
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
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
                $R = $this->A->rowAdd($i, $j, $k);
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
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
            try
            {
                $R = $this->A->rowAddScalar($m, $k);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
            try
            {
                $R = $this->A->rowSubtract($i, $j, $k);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
            try
            {
                $R = $this->A->rowSubtractScalar($m, $k);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $R = $this->A->columnAdd($i, $j, $k);
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
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
            try
            {
                $R = $this->A->columnMultiply($n, $k);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $R = $this->A->adjugate();
            } catch (BadParameterException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->augment($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->augmentLeft($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->augmentBelow($B);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $B = MatrixFactory::createNumeric([
                    [3, 4],
                    [5, 6],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
            {
            }

            // When
            try
            {
                $R = $this->A->augmentAbove($B);
            } catch (BadDataException $e)
            {
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (MathException $e)
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
            try
            {
                $R = $this->A->transpose();
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
                $B = MatrixFactory::createNumeric([
                    [3],
                ]);
            } catch (BadDataException $e)
            {
            } catch (MathException $e)
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
            try
            {
                $R = $this->A->map($f);
            } catch (IncorrectTypeException $e)
            {
            }

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
            try
            {
                $R = $this->A->rowInterchange($i, $j);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
            try
            {
                $R = $this->A->rowExclude($i);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
            try
            {
                $R = $this->A->columnInterchange($i, $j);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
            try
            {
                $R = $this->A->columnExclude($i);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            }

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
            try
            {
                $R = $this->A->leadingPrincipalMinor($k);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (OutOfBoundsException $e)
            {
            }

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
            try
            {
                $R = $this->A->meanDeviationOfRowVariables();
            } catch (IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }

        /**
         * @test meanDeviationOfColumnVariables propagates error tolerance to resultant matrix
         */
        public function testMeanDeviationOfColumnVariables()
        {
            // When
            try
            {
                $R = $this->A->meanDeviationOfColumnVariables();
            } catch (IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals(self::ε, $R->getError());
        }
    }
