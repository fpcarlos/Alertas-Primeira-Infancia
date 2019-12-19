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
class OrigemDadosPessoaPieChartView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/google_pie_chart.html');
        $data = array();
        if(isset($_GET['pessoa_id'])){
           $key = $_GET['pessoa_id'];                        
         }
        //var_dump($key);
        try{
               
           TTransaction::open('dbpmbv');
           $conn = TTransaction::get();
           
           $sql6="select a.nome,  count(*) qtd, round(count(a.nome) / SUM(count(*)) OVER () * 100, 2) AS pct
                    from scperfil.sistemas a
                    join scperfil.pessoas_sistemas b on a.id=b.sistema_id
                    join scperfil.tempo c on b.data_cadastro_sistema=c.datainfo
                    where b.pessoa_id=$key
                    group by a.nome
                    order by a.nome";             
            $stmt6 = $conn->prepare($sql6);
            $stmt6->execute();
            $results6 = $stmt6->fetchAll();
            //var_dump($results6);
            $ano=date('Y');
            $data[] =['Sistema','Qtd Eventos'];
            foreach($results6 as $row){
                          
                          $data[]=[$row[0],$row[1]];                
                                          
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
        
        $panel = new TPanelGroup('Sistemas identificados');
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
        
        $container->add($panel);
        parent::add($container);
    }
}
