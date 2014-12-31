<?php
class LandBattle extends Battle{

    static function playAs($name, $wargame, $arg = false)
    {
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
    }

    public function save()
    {
        $data = new stdClass();
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        return $data;
    }

    function poke($event, $id, $x, $y, $user, $click)
    {

        $playerId = $this->gameRules->attackingForceId;
        if ($this->players[$this->gameRules->attackingForceId] != $user) {
            return false;
        }

        switch ($event) {
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
                $mapGrid->setPixels($x, $y);
                return $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:
            /* fall through */
            case COMBAT_PIN_EVENT:

            return $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);

                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);
                break;

            case KEYPRESS_EVENT:
                $this->gameRules->processEvent(KEYPRESS_EVENT, $id, null, $click);
                break;

        }
        return true;
    }
}
