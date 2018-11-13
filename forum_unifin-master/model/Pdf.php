<?php

class Pdf 
{
    public function geraPdfComentarios($codigo_tema)
    {
        $comentarios = Comentario::listar(null, null, null, null, null, $codigo_tema);
        $tema = Tema::listar($codigo_tema);
        $titulo = "Relatório dos comentários do tema \"". $tema[0]['tem_descricao'] . "\"";

        $fpdf = new PDF_MC_Table();
        $fpdf->AddPage();
        $fpdf->SetWidths(Array(30, 110, 50));
        $fpdf->SetLineHeight(10);
        
        $fpdf->SetFont("Arial", "B", 14);
        $fpdf->MultiCell(190, 5, utf8_decode($titulo), 0);
        $fpdf->Ln(15);

        $fpdf->SetFont("Arial", "B", 10);
        $fpdf->Cell(70, 10, "Categoria", "1", 0, 'C');
        $fpdf->Cell(70, 10, "Tema", "1", 0, 'C');
        $fpdf->Cell(50, 10, utf8_decode("Data de criação"), "1", 1, 'C');

        $fpdf->SetFont("Arial", "", 10);

        $fpdf->SetWidths(Array(70, 70, 50));
        $fpdf->SetAligns(Array('L', 'L', 'C'));
        $fpdf->SetLineHeight(10);

        $fpdf->Row(Array(
            utf8_decode($tema[0]['cat_descricao']),
            utf8_decode($tema[0]['tem_descricao']),
            date('d/m/Y', strtotime($tema[0]["tem_criacao"]))
        ));
        $fpdf->Ln(15);

        $fpdf->SetFont("Arial", "B", 10);
        $fpdf->Cell(30, 10, "Autor", "1", 0, 'C');
        $fpdf->Cell(110, 10, utf8_decode("Comentário"), "1", 0, 'C');
        $fpdf->Cell(50, 10, utf8_decode("Data"), "1", 1, 'C');

        $fpdf->SetWidths(Array(30, 110, 50));
        $fpdf->SetAligns(Array('L', 'L', 'C'));
        $fpdf->SetLineHeight(10);

        $fpdf->SetFont("Arial", "", 10);
        foreach ($comentarios as $comentario)
        {
            $fpdf->Row(Array(
                utf8_decode($comentario['usu_login']),
                utf8_decode($comentario['com_comentario']),
                date('d/m/Y H:i', strtotime($comentario["com_datahora"])),
            ));
        }

        $arquivo = "relatorio_comentarios.pdf";
        $tipo_pdf = "I";
        $fpdf->SetSubject("Relatório dos comentários", true);
        $fpdf->SetKeywords("comentários tema");
        $fpdf->SetCreator("Forum Unifin");
        $fpdf->SetTitle($titulo, true);
        $fpdf->Output($arquivo, $tipo_pdf, true);
    }

    public function geraPdfTemas()
    {
        $temas = Tema::listar();
        $titulo = "Relatório dos temas do fórum";

        $fpdf = new PDF_MC_Table();
        $fpdf->AddPage();
        $fpdf->SetFont("Arial", "B", 14);
        $fpdf->Cell(190, 5, utf8_decode($titulo), 0, 1, 'C');
        $fpdf->Ln(15);

        $fpdf->SetFont("Arial", "B", 10);
        $fpdf->Cell(55, 10, "Categoria", "1", 0, 'C');
        $fpdf->Cell(55, 10, "Tema", "1", 0, 'C');
        $fpdf->Cell(50, 10, utf8_decode("Data"), "1", 0, 'C');
        $fpdf->Cell(30, 10, utf8_decode("Situação"), "1", 1, 'C');

        $fpdf->SetWidths(Array(55, 55, 50, 30));
        $fpdf->SetAligns(Array('L', 'L', 'C', 'C'));
        $fpdf->SetLineHeight(10);

        $fpdf->SetFont("Arial", "", 10);
        foreach ($temas as $tema)
        {            
            $aux = $tema['tem_ativo'] == 'S' ? 'Ativo' : 'Inativo';
            $fpdf->Row(Array(
                utf8_decode($tema['cat_descricao']),
                utf8_decode($tema['tem_descricao']),
                date('d/m/Y', strtotime($tema["tem_criacao"])),
                $aux,
            ));

        }

        $arquivo = "relatorio_temas.pdf";
        $tipo_pdf = "I";
        $fpdf->SetSubject("Relatório dos temas", true);
        $fpdf->SetKeywords("temas forum");
        $fpdf->SetCreator("Forum Unifin");
        $fpdf->SetTitle($titulo, true);
        $fpdf->Output($arquivo, $tipo_pdf, true);
    }
}