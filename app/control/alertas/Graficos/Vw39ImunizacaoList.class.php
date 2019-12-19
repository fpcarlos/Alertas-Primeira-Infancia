<?php
/**
 * Vw39ImunizacaoList Listing
 * @author  <your name here>
 */
class Vw39ImunizacaoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Vw39ImunizacaoPrimerissimaInfancia');
        $this->form->setFormTitle('A39 - Imunização na primerissima infância');
        

        // create the form fields
        $pessoa_id = new TEntry('pessoa_id');
        $nome = new TEntry('nome');
        $mae = new TEntry('mae');
        $cns = new TEntry('cns');
        $idade = new TEntry('idade');
        $vacina = new TEntry('vacina');
        $qtd = new TEntry('qtd');


        // add the fields
        $this->form->addFields( [ new TLabel('Pessoa Id') ], [ $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Mae') ], [ $mae ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );
        $this->form->addFields( [ new TLabel('Idade') ], [ $idade ] );
        $this->form->addFields( [ new TLabel('Vacina') ], [ $vacina ] );
        $this->form->addFields( [ new TLabel('Qtd') ], [ $qtd ] );


        // set sizes
        $pessoa_id->setSize('100%');
        $nome->setSize('100%');
        $mae->setSize('100%');
        $cns->setSize('100%');
        $idade->setSize('100%');
        $vacina->setSize('100%');
        $qtd->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Vw39ImunizacaoPrimerissimaInfancia_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addActionLink(_t('New'), new TAction(['Vw39ImunizacaoPrimerissimaInfanciaForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'right');
        $column_sistema_id = new TDataGridColumn('sistema_id', 'Sistema Id', 'right');
        $column_evento_id = new TDataGridColumn('evento_id', 'Evento Id', 'right');
        $column_tempo_id = new TDataGridColumn('tempo_id', 'Tempo Id', 'right');
        $column_ano = new TDataGridColumn('ano', 'Ano', 'right');
        $column_mes_desc = new TDataGridColumn('mes_desc', 'Mes Desc', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'left');
        $column_mae = new TDataGridColumn('mae', 'Mae', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_descricao = new TDataGridColumn('descricao', 'Descricao', 'left');
        $column_idade = new TDataGridColumn('idade', 'Idade', 'right');
        $column_vacina = new TDataGridColumn('vacina', 'Vacina', 'left');
        $column_qtd = new TDataGridColumn('qtd', 'Qtd', 'right');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_sistema_id);
        $this->datagrid->addColumn($column_evento_id);
        $this->datagrid->addColumn($column_tempo_id);
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_mes_desc);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_mae);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_idade);
        $this->datagrid->addColumn($column_vacina);
        $this->datagrid->addColumn($column_qtd);

        
        // create EDIT action
        //$action_edit = new TDataGridAction(['Vw39ImunizacaoPrimerissimaInfanciaForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        //$action_edit->setLabel(_t('Edit'));
        //$action_edit->setImage('fa:pencil-square-o blue fa-lg');
        //$action_edit->setField('pessoa_id');
        //$this->datagrid->addAction($action_edit);
        
        $tipo='A39 - Imunização na primerissima infância';
        $action_select2 = new TDataGridAction(array('Painel02View', 'onReload'));
        $action_select2->setUseButton(TRUE);
        $action_select2->setButtonClass('nopadding');
        $action_select2->setLabel('Perfil');
        $action_select2->setImage('fa:hand-pointer-o red');
        $action_select2->setField('pessoa_id');
        $action_select2->setField('sistema_id');
        $action_select2->setField('evento_id');
        $action_select2->setParameter('tipo',$tipo);
        //$action_select2->setField('tipo','A23 - Gestantes adolescentes fora do FQA');
        $this->datagrid->addAction($action_select2);
        
        $action_select = new TDataGridAction(array('PessoasFormList', 'onEdit'));
        $action_select->setUseButton(TRUE);
        $action_select->setButtonClass('nopadding');
        $action_select->setLabel('Cadastro');
        $action_select->setImage('fa:hand-pointer-o green');
        $action_select->setField('pessoa_id');
        $this->datagrid->addAction($action_select);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('dbpmbv'); // open a transaction with database
            $object = new Vw39ImunizacaoPrimerissimaInfancia($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('Vw39ImunizacaoList_filter_pessoa_id',   NULL);
        TSession::setValue('Vw39ImunizacaoList_filter_nome',   NULL);
        TSession::setValue('Vw39ImunizacaoList_filter_mae',   NULL);
        TSession::setValue('Vw39ImunizacaoList_filter_cns',   NULL);
        TSession::setValue('Vw39ImunizacaoList_filter_idade',   NULL);
        TSession::setValue('Vw39ImunizacaoList_filter_vacina',   NULL);
        TSession::setValue('Vw39ImunizacaoList_filter_qtd',   NULL);

        if (isset($data->pessoa_id) AND ($data->pessoa_id)) {
            $filter = new TFilter('pessoa_id', '=', "$data->pessoa_id"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_pessoa_id',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'ilike', "%{$data->nome}%"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->mae) AND ($data->mae)) {
            $filter = new TFilter('mae', 'ilike', "%{$data->mae}%"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_mae',   $filter); // stores the filter in the session
        }


        if (isset($data->cns) AND ($data->cns)) {
            $filter = new TFilter('cns', 'ilike', "%{$data->cns}%"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_cns',   $filter); // stores the filter in the session
        }


        if (isset($data->idade) AND ($data->idade)) {
            $filter = new TFilter('idade', '=', "$data->idade"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_idade',   $filter); // stores the filter in the session
        }


        if (isset($data->vacina) AND ($data->vacina)) {
            $filter = new TFilter('vacina', 'ilike', "%{$data->vacina}%"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_vacina',   $filter); // stores the filter in the session
        }


        if (isset($data->qtd) AND ($data->qtd)) {
            $filter = new TFilter('qtd', 'ilike', "%{$data->qtd}%"); // create the filter
            TSession::setValue('Vw39ImunizacaoList_filter_qtd',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Vw39ImunizacaoPrimerissimaInfancia_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'dbpmbv'
            TTransaction::open('dbpmbv');
            
            // creates a repository for Vw39ImunizacaoPrimerissimaInfancia
            $repository = new TRepository('Vw39ImunizacaoPrimerissimaInfancia');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'pessoa_id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('Vw39ImunizacaoList_filter_pessoa_id')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_pessoa_id')); // add the session filter
            }


            if (TSession::getValue('Vw39ImunizacaoList_filter_nome')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_nome')); // add the session filter
            }


            if (TSession::getValue('Vw39ImunizacaoList_filter_mae')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_mae')); // add the session filter
            }


            if (TSession::getValue('Vw39ImunizacaoList_filter_cns')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_cns')); // add the session filter
            }


            if (TSession::getValue('Vw39ImunizacaoList_filter_idade')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_idade')); // add the session filter
            }


            if (TSession::getValue('Vw39ImunizacaoList_filter_vacina')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_vacina')); // add the session filter
            }


            if (TSession::getValue('Vw39ImunizacaoList_filter_qtd')) {
                $criteria->add(TSession::getValue('Vw39ImunizacaoList_filter_qtd')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    



    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
