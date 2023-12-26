<?php
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

?>