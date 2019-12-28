<?php
declare(strict_types=1);

namespace App\Service;

/**
 * Class SalaryDateService
 *
 * @package App\Service
 */
class SalaryDateService
{
    /**
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function jumpToNextSalaryDate(\DateTime $dateTime): \DateTime
    {
        return $dateTime
            ->modify('last day of this month')
            ->modify('next weekday')
        ;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function jumpToNextBonusSalaryDate(\DateTime $dateTime): \DateTime
    {
        return $dateTime->setDate(
            (int)$dateTime->format('Y'),
            (int)$dateTime->format('n'),
            14
        )->modify('next weekday');
    }
}
