<?php
/**
 * Chart
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class VulnerabilidadeDadosPieChartView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/google_bar_chart.html');
        $data = array();
        
        try{
               
           TTransaction::open('dbpmbv');
           $conn = TTransaction::get();
           
           $sql6="select 'Gestantes em vunerabilidade fora do FQA' nome, count(*) qtd
from scperfil.vw_37_vunerabilidade_gestante";  
           $sql6="select mes, concat(mes_desc,'/',ano) mesano, count(*) qtd
from scperfil.vw_37_vunerabilidade_gestante
where ano=(extract(year from current_date)) and mes>=(extract(month from current_date)-9)
group by mes_desc, ano, mes
order by 1";           
            $stmt6 = $conn->prepare($sql6);
            $stmt6->execute();
            $results6 = $stmt6->fetchAll();
            //var_dump($results6);
            $ano=date('Y');
            $data[] =['Sistema','Qtd Cadastro'];
            foreach($results6 as $row){
                          
                          $data[]=[$row[1],$row[2]];                
                                          
                                      }
            
            
           TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());

            TTransaction::rollback();
        }
        
        /*
        $data[] = [ 'Pessoa', 'Value' ];
        $data[] = [ 'Pedro',   40 ];
        $data[] = [ 'Maria',   30 ];
        $data[] = [ 'João',    30 ];
        */
        # PS: If you use values from database ($row['total'), 
        # cast to float. Ex: (float) $row['total']
        
        $panel = new TPanelGroup('37 Gestantes em vunerabilidade fora do FQA');
        $panel->style = 'width: 100%';
        $panel->add($html);
        
        // replace the main section variables
        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Sistemas',
                                           'ytitle' => 'Accesses', 
                                           'xtitle' => 'Day',
                                           'uniqid' => uniqid()));
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        if ($show_breadcrumb)
        {
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($panel);
        parent::add($container);
    }
}
