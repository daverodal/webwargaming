<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 7:10 PM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Class Combat
{
    public $attackers;
    public $defenders;
    public $index;
    public $attackStrength;
    public $defenseStrength;
    public $Die;
    public $combatResult;
    public $thetas;
    public $useAlt = false;
    public $useDetermined = false;
    public $isBombardment = false;
    public $pinCRT = false;
    public $dieShift = 0;
    public $dayTime = false;
    public $unitDefenseStrength;

    public function __construct()
    {
        $this->attackers = new stdClass();
        $this->defenders = new stdClass();
        $this->thetas = new stdClass();
    }
}
