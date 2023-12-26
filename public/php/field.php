<?php

class Field
{
    public $figure;
    public $state;
    public $X;
    public $Y;

    public function __construct($x, $y, $figure = null)
    {
        $this->figure = $figure;
        $this->state = "Nothing";
        $this->X = $x;
        $this->Y = $y;
    }

    public function getStyle(): string
    {
        $style = "";
        if ($this->state === "Ready") {
            $style .= "border: rgba(255, 255, 0, 0.798) 5px solid;";
        } elseif ($this->state === "Waiting") {
            $style .= "border: rgba(0, 255, 68, 0.798) 5px solid;";
        }

        return $style;
    }

    public function click(Chessboard $Ch)
    {
        if ($this->state === "Waiting") {
            $this->swap($Ch);
            $Ch->deletingStates();
            $Ch->IsWhitemove = !$Ch->IsWhitemove;
        } elseif ($this->state === "Ready") {
            $Ch->deletingStates();
        } elseif ($this->figure != null) {
            $Ch->deletingStates();
            if ($Ch->IsWhitemove === $this->figure->white) {
                $this->figure->Active($Ch, $this->X, $this->Y);
                $this->state = "Ready";
            }
        } else {
            $Ch->deletingStates();
        }
    }

    private function swap(Chessboard $Ch)
    {
        $activeField = $Ch->findActive();
        $temp = $activeField->figure;
        $activeField->figure = $this->figure;
        $this->figure = $temp;
    }
}
