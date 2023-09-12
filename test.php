<?php

    require_once __DIR__.'/vendor/autoload.php';

    use MathPHP\Algebra;

    // TEST
// Greatest common divisor (GCD)
    $gcd = Algebra::gcd(8, 12);

// Extended greatest common divisor - gcd(a, b) = a*a' + b*b'
    $gcd = Algebra::extendedGcd(12, 8); // returns array [gcd, a', b']

// Least common multiple (LCM)
    $lcm = Algebra::lcm(5, 2);

// Factors of an integer
    $factors = Algebra::factors(12); // returns [1, 2, 3, 4, 6, 12]

// Linear equation of one variable: ax + b = 0
    [$a, $b] = [2, 4]; // 2x + 4 = 0
    $x = Algebra::linear($a, $b);

// Quadratic equation: ax² + bx + c = 0
    [$a, $b, $c] = [1, 2, -8]; // x² + 2x - 8
    [$x₁, $x₂] = Algebra::quadratic($a, $b, $c);

// Discriminant: Δ = b² - 4ac
    [$a, $b, $c] = [2, 3, 4]; // 3² - 4(2)(4)
    $Δ = Algebra::discriminant($a, $b, $c);
    print_r($Δ);

// Cubic equation: z³ + a₂z² + a₁z + a₀ = 0
    [$a₃, $a₂, $a₁, $a₀] = [2, 9, 3, -4]; // 2x³ + 9x² + 3x -4
    [$x₁, $x₂, $x₃] = Algebra::cubic($a₃, $a₂, $a₁, $a₀);

// Quartic equation: a₄z⁴ + a₃z³ + a₂z² + a₁z + a₀ = 0
    [$a₄, $a₃, $a₂, $a₁, $a₀] = [
        1,
        -10,
        35,
        -50,
        24,
    ]; // z⁴ - 10z³ + 35z² - 50z + 24 = 0
    [$z₁, $z₂, $z₃, $z₄] = Algebra::quartic($a₄, $a₃, $a₂, $a₁, $a₀);

    print_r([$z₁, $z₂, $z₃, $z₄]);
