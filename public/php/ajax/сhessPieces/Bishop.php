<?php

class Bishop extends Figure
{
	public function Active(Chessboard $Ch, $X, $Y)
	{
		$direction = $this->white ? 1 : -1;
		$this->ProcessLine($Ch, $X, $Y, 1, 1 * $direction);
		$this->ProcessLine($Ch, $X, $Y, -1, 1 * $direction);
	}
}

?>