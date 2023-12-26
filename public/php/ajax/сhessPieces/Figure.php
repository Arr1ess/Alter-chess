<?php

class Figure
{
    public $white;
    public $Image;

    public function __construct($white, $Image)
    {
        $this->white = $white;
        $this->Image = $Image;
    }

    public function ProcessField(Chessboard $Ch, $X, $Y)
    {
        if (isset($Ch->Fields[$Y * 8 + $X]->figure) && $Ch->Fields[$Y * 8 + $X]->figure === null) {
            $Ch->Fields[$Y * 8 + $X]->state = "Waiting";
            return true;
        } else {
            if ($Ch->Fields[$Y * 8 + $X]->figure->white != $this->white) {
                $Ch->Fields[$Y * 8 + $X]->state = "Waiting";
            }
            return false;
        }
    }

    public function ProcessLine(Chessboard $Ch, $X, $Y, $dX, $dY)
    {
        for ($i = 1; $X + $i * $dX >= 0 && $X + $i * $dX < 8 && $Y + $i * $dY >= 0 && $Y + $i * $dY < 8; $i++) {
            if (!$this->ProcessField($Ch, $X + $i * $dX, $Y + $i * $dY)) {
                break;
            }
        }
    }
}

class Bishop extends Figure
{
    public function Active(Chessboard $Ch, $X, $Y)
    {
        $direction = $this->white ? 1 : -1;
        $this->ProcessLine($Ch, $X, $Y, 1, 1 * $direction);
        $this->ProcessLine($Ch, $X, $Y, -1, 1 * $direction);
    }
}

class Knight extends Figure
{
    public function Active(Chessboard $Ch, $X, $Y)
    {
        $direction = $this->white ? 1 : -1;
        $this->TryMove($Ch, $X + 2, $Y + 1 * $direction);
        $this->TryMove($Ch, $X - 2, $Y + 1 * $direction);
        $this->TryMove($Ch, $X + 1, $Y + 2 * $direction);
        $this->TryMove($Ch, $X - 1, $Y + 2 * $direction);
    }

    private function TryMove(Chessboard $Ch, $X, $Y)
    {
        if ($X >= 0 && $X < 8 && $Y >= 0 && $Y < 8 && ($Ch->Fields[$Y * 8 + $X]->figure == null || $Ch->Fields[$Y * 8 + $X]->figure->white != $this->white)) {
            $Ch->Fields[$Y * 8 + $X]->state = "Waiting";
        }
    }
}

class King extends Figure
{
    public function Active(Chessboard $Ch, $X, $Y)
    {
        for ($i = max($X - 1, 0); $i <= min($X + 1, 7); $i++) {
            for ($j = max($Y - 1, 0); $j <= min($Y + 1, 7); $j++) {
                $this->ProcessField($Ch, $i, $j);
            }
        }
    }
}

class Pawn extends Figure
{
    public function Active(Chessboard $Ch, $X, $Y)
    {
        if ($this->white && $Y < 7) {
            if ($Ch->Fields[($Y + 1) * 8 + $X]->figure == null) {
                $Ch->Fields[($Y + 1) * 8 + $X]->state = "Waiting";
                if ($Y == 1 && $Ch->Fields[($Y + 2) * 8 + $X]->figure == null) {
                    $Ch->Fields[($Y + 2) * 8 + $X]->state = "Waiting";
                }
            }
            if ($X < 7 && $Ch->Fields[($Y + 1) * 8 + ($X + 1)]->figure != null && $Ch->Fields[($Y + 1) * 8 + ($X + 1)]->figure->white != $this->white) {
                $Ch->Fields[($Y + 1) * 8 + ($X + 1)]->state = "Waiting";
            }
            if ($X > 0 && $Ch->Fields[($Y + 1) * 8 + ($X - 1)]->figure != null && $Ch->Fields[($Y + 1) * 8 + ($X - 1)]->figure->white != $this->white) {
                $Ch->Fields[($Y + 1) * 8 + ($X - 1)]->state = "Waiting";
            }
        } else if (!$this->white && $Y > 0) {
            if ($Ch->Fields[($Y - 1) * 8 + $X]->figure == null) {
                $Ch->Fields[($Y - 1) * 8 + $X]->state = "Waiting";
                if ($Y == 6 && $Ch->Fields[($Y - 2) * 8 + $X]->figure == null) {
                    $Ch->Fields[($Y - 2) * 8 + $X]->state = "Waiting";
                }
            }
            if ($X < 7 && $Ch->Fields[($Y - 1) * 8 + ($X + 1)]->figure != null && $Ch->Fields[($Y - 1) * 8 + ($X + 1)]->figure->white != $this->white) {
                $Ch->Fields[($Y - 1) * 8 + ($X + 1)]->state = "Waiting";
            }
            if ($X > 0 && $Ch->Fields[($Y - 1) * 8 + ($X - 1)]->figure != null && $Ch->Fields[($Y - 1) * 8 + ($X - 1)]->figure->white != $this->white) {
                $Ch->Fields[($Y - 1) * 8 + ($X - 1)]->state = "Waiting";
            }
        }
    }
}

class Queen extends Figure
{
    public function Active(Chessboard $Ch, $X, $Y)
    {
        $this->ProcessLine($Ch, $X, $Y, 0, 1);
        $this->ProcessLine($Ch, $X, $Y, 0, -1);
        $this->ProcessLine($Ch, $X, $Y, 1, 0);
        $this->ProcessLine($Ch, $X, $Y, -1, 0);
        $this->ProcessLine($Ch, $X, $Y, 1, 1);
        $this->ProcessLine($Ch, $X, $Y, -1, -1);
        $this->ProcessLine($Ch, $X, $Y, 1, -1);
        $this->ProcessLine($Ch, $X, $Y, -1, 1);
    }
}

class Rook extends Figure
{
    public function Active(Chessboard $Ch, $X, $Y)
    {
        for ($i = $X - 1; $i >= 0; $i--) {
            if (!$this->ProcessField($Ch, $i, $Y)) {
                break;
            }
        }
        for ($i = $X + 1; $i < 8; $i++) {
            if (!$this->ProcessField($Ch, $i, $Y)) {
                break;
            }
        }
        for ($j = $Y - 1; $j >= 0; $j--) {
            if (!$this->ProcessField($Ch, $X, $j)) {
                break;
            }
        }
        for ($j = $Y + 1; $j < 8; $j++) {
            if (!$this->ProcessField($Ch, $X, $j)) {
                break;
            }
        }
    }
}

?>