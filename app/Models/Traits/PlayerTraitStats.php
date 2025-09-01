<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait PlayerTraitStats {
    private function getTechnicalValues() {
        $stats = $this->stats ?? [];
        return $stats['technical'] ?? [];
    }

    private function getMentalValues() {
        $stats = $this->stats ?? [];
        return $stats['mental'] ?? [];
    }

    private function getPhysicalValues() {
        $stats = $this->stats ?? [];
        return $stats['physical'] ?? [];
    }

    private function getGoalkeepingValues() {
        $stats = $this->stats ?? [];
        return $stats['goalkeeping'] ?? [];
    }

    public function getTechnicalAttribute() {
        return $this->getTechnicalValues();
    }

    public function getMentalAttribute() {
        return $this->getMentalValues();
    }

    public function getPhysicalAttribute() {
        return $this->getPhysicalValues();
    }
}
