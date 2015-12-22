<?php

namespace SPI\FinalChapter;

    class UnitFactory
    {
        public static $id = 0;
        public static $injector;

        public static function build($data = false)
        {

            $sU = new \SPI\FinalChapter\SimpleUnit($data);
            if ($data === false) {
                $sU->id = self::$id++;
            }
            return $sU;
        }

        public static function create($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitDefStrength, $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $class, $unitDesig = "")
        {
            $unit = self::build();
            $unit->set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitDefStrength, $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality, true, $class, $unitDesig);
            self::$injector->injectUnit($unit);
        }

    }
