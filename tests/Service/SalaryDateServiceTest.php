<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\SalaryDateService;
use PHPUnit\Framework\TestCase;

/**
 * Class SalaryDateServiceTest
 *
 * @package App\Tests\Service
 */
class SalaryDateServiceTest extends TestCase
{

    /**
     * @var \App\Service\SalaryDateService
     */
    private $salaryDateService;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->salaryDateService = new SalaryDateService;
    }

    /**
     *
     */
    public function testNextSalaryDateFirstWeekday(): void
    {
        $date = new \DateTime('2020-01-01');
        $salaryDate = $this->salaryDateService->jumpToNextSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'First jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'First jump must not be Sunday');
        $this->assertGreaterThanOrEqual(1, intval($salaryDate->format('d')), 'First jump must be the first or higher');
        $this->assertLessThanOrEqual(3, intval($salaryDate->format('d')), 'First jump must be lower or equal than 3');

        $salaryDate = $this->salaryDateService->jumpToNextSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'Second jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'Second jump must not be Sunday');
        $this->assertGreaterThanOrEqual(1, intval($salaryDate->format('d')), 'Second jump must be the first or higher');
        $this->assertLessThanOrEqual(3, intval($salaryDate->format('d')), 'Second jump must be lower or equal than 3');

        $salaryDate = $this->salaryDateService->jumpToNextSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'Third jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'Third jump must not be Sunday');
        $this->assertGreaterThanOrEqual(1, intval($salaryDate->format('d')), 'Third jump must be the first or higher');
        $this->assertLessThanOrEqual(3, intval($salaryDate->format('d')), 'Third jump must be lower or equal than 3');
    }

    /**
     *
     */
    public function testNextSalaryDateMidWeekday(): void
    {
        $date = new \DateTime('2020-01-01');
        $salaryDate = $this->salaryDateService->jumpToNextBonusSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'First jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'First jump must not be Sunday');
        $this->assertGreaterThanOrEqual(15, intval($salaryDate->format('d')), 'First jump must be the 15th or higher');
        $this->assertLessThanOrEqual(18, intval($salaryDate->format('d')), 'First jump must be lower or equal than 18');

        $salaryDate = $this->salaryDateService->jumpToNextBonusSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'Second jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'Second jump must not be Sunday');
        $this->assertGreaterThanOrEqual(15, intval($salaryDate->format('d')), 'Second jump must be the 15th or higher');
        $this->assertLessThanOrEqual(18, intval($salaryDate->format('d')), 'Second jump must be lower or equal than 18');

        $salaryDate = $this->salaryDateService->jumpToNextBonusSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'Third jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'Third jump must not be Sunday');
        $this->assertGreaterThanOrEqual(15, intval($salaryDate->format('d')), 'Third jump must be the 15th or higher');
        $this->assertLessThanOrEqual(18, intval($salaryDate->format('d')), 'Third jump must be lower or equal than 18');
    }

    /**
     *
     */
    public function testNextSalaryDateSequence(): void
    {
        $date = new \DateTime('2020-08-22');
        $salaryDate = $this->salaryDateService->jumpToNextSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'First jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'First jump must not be Sunday');
        $this->assertGreaterThanOrEqual(1, intval($salaryDate->format('d')), 'First jump must be the first or higher');
        $this->assertLessThanOrEqual(3, intval($salaryDate->format('d')), 'First jump must be lower or equal than 3');

        $salaryDate = $this->salaryDateService->jumpToNextBonusSalaryDate($date);
        $this->assertNotSame('Saturday', $salaryDate->format('l'), 'Second jump must not be Saturday');
        $this->assertNotSame('Sunday', $salaryDate->format('l'), 'Second jump must not be Sunday');
        $this->assertGreaterThanOrEqual(15, intval($salaryDate->format('d')), 'Second jump must be the 15th or higher');
        $this->assertLessThanOrEqual(18, intval($salaryDate->format('d')), 'Second jump must be lower or equal than 18');
    }

    /**
     *
     */
    protected function tearDown()
    {
        $this->salaryDateService = null;
        parent::tearDown();
    }
}
