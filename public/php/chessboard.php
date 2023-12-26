<?php
include __DIR__ .  'field.php';
include __DIR__ .'chessPieces/Figure.php';
// class Rook{
//     public $white;
//     public $Image;

//     public function __construct($white, $Image)
//     {
//         $this->white = $white;
//         $this->Image = $Image;
//     }
// }
class Chessboard
{
    public $Size = 600;
    public $Fields;
    public $IsWhitemove = true;

    public function __construct()
    {
        $this->Fields = array();
        // for ($i = 0; $i < 8; $i++) {
        //     $this->Fields[1 * 8 + $i] = new Field($i, 1, new Pawn(true, "pawn_white.png"));
        //     $this->Fields[6 * 8 + $i] = new Field($i, 6, new Pawn(false, "pawn_black.png"));
        // }
                    //$rook = new Figure(true, "rook_white.png");
            //$r = new Rook(true,  "rook_white.png");
       
        
         //$this->Fields[0] = new Field(0, 0, 5);
        // $this->Fields[1] = new Field(1, 0, new Knight(true, "knight_white.png"));
        // $this->Fields[2] = new Field(2, 0, new Bishop(true, "bishop_white.png"));
        // $this->Fields[3] = new Field(3, 0, new Queen(true, "queen_white.png"));
        // $this->Fields[4] = new Field(4, 0, new King(true, "king_white.png"));
        // $this->Fields[5] = new Field(5, 0, new Bishop(true, "bishop_white.png"));
        // $this->Fields[6] = new Field(6, 0, new Knight(true, "knight_white.png"));
        // $this->Fields[7] = new Field(7, 0, new Rook(true, "rook_white.png"));

        // $this->Fields[56] = new Field(0, 7, new Rook(false, "rook_black.png"));
        // $this->Fields[57] = new Field(1, 7, new Knight(false, "knight_black.png"));
        // $this->Fields[58] = new Field(2, 7, new Bishop(false, "bishop_black.png"));
        // $this->Fields[59] = new Field(3, 7, new Queen(false, "queen_black.png"));
        // $this->Fields[60] = new Field(4, 7, new King(false, "king_black.png"));
        // $this->Fields[61] = new Field(5, 7, new Bishop(false, "bishop_black.png"));
        // $this->Fields[62] = new Field(6, 7, new Knight(false, "knight_black.png"));
        // $this->Fields[63] = new Field(7, 7, new Rook(false, "rook_black.png"));

        for ($i = 0; $i < 8; $i++) {
            for ($j = 2; $j < 6; $j++) {
                $this->Fields[$j * 8 + $i] = new Field($i, $j, null);
            }
        }

    }

    public function getPadding(): int
    {
        return (int) ($this->Size * 0.1);
    }

    public function getStyle(): string
    {
        return "width: " . $this->Size . "px; height: " . $this->Size . "px;";
    }

    public function deletingStates()
    {
        foreach ($this->Fields as $field) {
            $field->state = "Nothing";
        }
    }

    public function findActive()
    {
        foreach ($this->Fields as $field) {
            if ($field->state == "Ready") {
                return $field;
            }
        }
        return null;
    }
}
?>