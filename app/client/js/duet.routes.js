var DUET = DUET || {};


DUET.routes = {

    //todo: add back default route, but dashboard should only run iff language and templates have been loaded - wait for them , '*path':'dashboard'
    routes:{
        ':referenceObjectPlural/:referenceId/discussion':'discussion',
        'projects/:id/:entityType/:entityId/discussion':'discussion',
        'projects/:id/tasks':'taskList',
        'invoices/:invoiceId/build': 'invoiceBuilder',
        'projects/:id/invoices/build': 'newInvoiceForProjectBuilder',
        'projects/:id/:entityType':'projectEntityList',
        'projects/:id/:entityType/:entityId':'projectEntity',

        'projects/:id/invoices/:invoiceId/:invoiceAction':'invoiceScreens',
        'projects/:id/estimates/:estimateId/:estimateAction':'estimateScreens',
        'projects/:id':'projectEntityList',
        'projects':'projectEntityList',
        'templates/:id/tasks':'templateTaskList',
        'templates/:id/:entityType':'templateEntityList',
        'templates/:id/:entityType/:entityId':'templateEntity',
        'templates/:id':'templateEntityList',
        'templates':'templateEntityList',
        'tasks/:id':'task',
        'tasks':'task',
        'clients/:id':'client',
        'clients':'client',
        'users/:id':'user',
        'users':'user',
        'invoices': 'invoice',
        'invoices/:id': 'invoice',
        'recurringinvoices': 'recurringInvoice',
        'recurringinvoices/new': 'newRecurringInvoice',
        'recurringinvoices/new/:invoiceId': 'newRecurringInvoice',
        'recurringinvoices/:id': 'recurringInvoice',
        'estimates':'estimate',
        'estimates/:id':'estimate',
        'files':'file',
        'files/:id':'file',
        'login':'login',
        'logout':'logout',
        'dashboard':'dashboard',
        'profile':'myProfile',
        'search/:query':'search',
        'forgot_password':'forgotPassword',
        'settings':'settings',
        'settings/*path':'settings',
        'reporting':'reporting',
        'discussion/:referenceObject/:referenceId':'discussion',
        'link/:slug':'link',
        '':'dashboard'
    },
    newInvoiceForProjectBuilder:function(projectId){

        var invoice, view;

        DUET.panelTwo.loading();
        DUET.panelOne.hide();

        var project = new DUET.Project();
        project.load(projectId);
        DUET.panelTwo.setModel(project);

        invoice = new DUET.Invoice();


        //prevent a user from opening up the build or import views
        //if opened, they still wouldn't be able to modify the invoice because it's restricted on the server side
        if (!DUET.userIsAdmin())
            return false;

        $.when(invoice.loadDefaults(projectId)).done(function () {
            view = new DUET.InvoiceEditorView(invoice);
            DUET.routeHelpers.panelTwoHandler({activeModel: 'invoices'}, view);
        });



    },
    invoiceBuilder:function(invoiceId){

        var view, invoice = new DUET.Invoice();

        function loadView(){
            view = new DUET.InvoiceEditorView(invoice);
            DUET.routeHelpers.panelTwoHandler({activeModel: 'invoices'}, view);
        }

        if (invoiceId) {
            invoice.on('loaded', function () {
                loadView();
            });

            invoice.load(invoiceId);
        }
        else {
            loadView();
        }
    },

    link:function(slug){
        var linkAsset = new DUET.LinkAsset();
        linkAsset.on('loaded', function(){

            $.when(DUET.getInitialInfo()).done(function () {
                if(DUET.isLoggedIn){
                    var type = linkAsset.type;
                    DUET.navigate('#' + type + 's/' + linkAsset[DUET.utils.ucFirst(type)].id);
                }
                else{
                    var linkView = new DUET.LinkView(linkAsset, slug);
                    linkView.addTo({$anchor: $('body')});
                }

            });

        });

        linkAsset.load(slug);


    },
    projectEntityList:function (projectId, entityType, projectType) {
        var args, panelLoaded, params;

        projectType = projectType || 'project';


        if (!projectId)
            panelLoaded = DUET.routeHelpers.initPrimaryPanel(projectType, projectId);
        else {

            DUET.panelTwo.loading();

            args = arguments;

            params = DUET.routeHelpers.initSecondaryPanel(args);

            function collectionHandler() {
                var collection, view, pluralModelName = params.activeModelSingular + 's';

                //because the word 'peoples' makes no sense
                if (pluralModelName == 'peoples')
                    pluralModelName = 'people';

                collection = new DUET.Collection({
                    model:params.activeModelSingular,
                    url:projectType + 's/' + projectId + '/' + pluralModelName
                });

                //todo: maybe some kind of loading text while the collection is loading for slow connections
                //TODO:clicking on any of these list items reloads the entire page. not cool
                collection.on('loaded', function () {
                    view = new DUET[DUET.utils.ucFirst(params.activeModelSingular) + 'ListView'](collection, params.project);
                    DUET.routeHelpers.panelTwoHandler(params, view);
                });

                collection.load();
            }

            function modelHandler() {
                var modelName, model, viewNamePrefix, view;

                modelName = params.activeModelName;
                if (DUET[modelName]) {
                    model = new DUET[modelName];
                }
                else {
                    modelName = ut.ucFirst(projectType) + params.activeModelName;
                    model = new DUET[modelName];
                }

                model.on('loaded', function () {
                    viewNamePrefix = DUET.utils.ucFirst(params.activeModelName);

                    if (DUET[viewNamePrefix + 'View'])
                        view = new DUET[viewNamePrefix + 'View'](model);
                    else view = new DUET['Project' + viewNamePrefix + 'View'](model);

                    DUET.routeHelpers.panelTwoHandler(params, view);
                });

                model.load(projectId);
            }


            if (params.project) {

                $.when(params.project.isLoaded).done(function () {
                    DUET.panelTwo.setTitle(params.project.name);
                    DUET.panelTwo.setModel(params.project);
                });

            }

            if (params.activeModel != 'calendar' && params.activeModel != 'details' && params.activeModel != 'notes' && params.activeModel != 'people') {
                collectionHandler();
            }
            else {
                modelHandler();
            }
        }
    },
    taskList:function(projectId, filter){
        var list = new DUET.List(projectId);

        var params = DUET.routeHelpers.initSecondaryPanel([projectId, 'tasks']);

        list.on('loaded', function(){
            var view = new DUET.TaskListView(list, params.project);
            DUET.routeHelpers.panelTwoHandler(params, view);
            DUET.panelTwo.setModel(params.project);
            DUET.panelTwo.setTitle(params.project.name);
        });

        list.load('');
    },
    templateTaskList:function(templateId){
        var list = new DUET.List(templateId, 'templates');

        var params = DUET.routeHelpers.initSecondaryPanel([templateId, 'tasks']);

        list.on('loaded', function () {
            var view = new DUET.TaskListView(list, params.project);
            DUET.routeHelpers.panelTwoHandler(params, view);
            DUET.panelTwo.setModel(params.project);
            DUET.panelTwo.setTitle(params.project.name);
        });

        list.load('');
    },
    projectEntity:function (projectId, entityType, entityId, projectType) {
        var args, panelLoaded, params;


        projectType = projectType || 'project';

        DUET.panelTwo.loading();

        args = arguments;

        params = DUET.routeHelpers.initSecondaryPanel(args);
        DUET.panelTwo.setModel(params.project);

        $.when(params.project.isLoaded).done(function () {


            var activeModelUppercase = DUET.utils.ucFirst(params.activeModelSingular);
            var model = new DUET[activeModelUppercase];

            model.on('loaded', function () {
                // DUET.context(params.activeModelSingular, model.id);
                var view = new DUET[activeModelUppercase + 'View'](model);
                DUET.panelTwo.setInnerContent(view);
                DUET.panelTwo.buildProjectItemCategories('project-' + params.activeModel);
            });

            model.load(params.activeModelId);
        });

    },
    templateEntityList:function (projectId, entityType) {
        DUET.routes.projectEntityList(projectId, entityType, 'template');
    },
    templateEntity:function (projectId, entityType, entityId) {
        DUET.routes.projectEntity(projectId, entityType, entityId, 'template');
    },
    invoiceScreens:function (projectId, invoiceId, invoiceAction) {
        var invoice, view, args, params; //todo:the primary panel probably isn't relevant here anymore.

        DUET.panelTwo.loading();

        args = arguments;

        invoice = new DUET.Invoice();
        params = DUET.routeHelpers.initSecondaryPanel(args);

        DUET.panelTwo.setModel(params.project);

        //prevent a user from opening up the build or import views
        //if opened, they still wouldn't be able to modify the invoice because it's restricted on the server side
        if (!DUET.userIsAdmin() && invoiceAction != 'preview')
            return false;

        invoice.on('loaded', function () {
            if (invoiceAction == 'build')
                view = new DUET.InvoiceEditorView(invoice);
            else if (invoiceAction == 'import')
                view = new DUET.InvoiceImportView(invoice);
            else if (invoiceAction == 'preview')
                view = new DUET.InvoicePreviewView(invoice);

            DUET.routeHelpers.panelTwoHandler(params, view);
        });

        invoice.load(invoiceId);
    },
    estimateScreens: function (projectId, invoiceId, invoiceAction) {
        var estimate, view, args, params; //todo:the primary panel probably isn't relevant here anymore.

        DUET.panelTwo.loading();

        args = arguments;

        estimate = new DUET.Estimate();
        params = DUET.routeHelpers.initSecondaryPanel(args);

        DUET.panelTwo.setModel(params.project);

        //prevent a user from opening up the build or import views
        //if opened, they still wouldn't be able to modify the estimate because it's restricted on the server side
        if (!DUET.userIsAdmin() && invoiceAction != 'preview')
            return false;

        estimate.on('loaded', function () {
            if (invoiceAction == 'build')
                view = new DUET.InvoiceEditorView(estimate);
            else if (invoiceAction == 'import')
                view = new DUET.InvoiceImportView(estimate);
            else if (invoiceAction == 'preview')
                view = new DUET.InvoicePreviewView(estimate);

            DUET.routeHelpers.panelTwoHandler(params, view);
        });

        estimate.load(invoiceId);

    },
    task:function (id) {
        DUET.baseModelRoute('task', id);
    },
    client:function (id) {
        DUET.baseModelRoute('client', id);
    },
    user:function (id) {
        DUET.baseModelRoute('user', id);
    },
     invoice: function (id) {

        if (id != 'paid' && id != 'overdue') {
            DUET.baseModelRoute('invoice', id);
        }
        else {
            DUET.panelOne.setList('invoices', id);
        }
    },
    recurringInvoice: function (id) {

        DUET.baseModelRoute('recurringinvoice', id);
    },
    newRecurringInvoiceFromInvoice:function(){

    },
    newRecurringInvoice:function(invoiceId){

        DUET.panelTwo.setModel();
        DUET.panelTwo.clearProjectItemCategories();
        DUET.panelOne.hide();

        function loadView(invoice){
            var newRecurringInvoiceView = new DUET.RecurringInvoiceEditorView(invoice);
            DUET.panelTwo.setContent(newRecurringInvoiceView);
        }

        var recurringInvoice = new DUET.RecurringInvoice();

        if(invoiceId){

            recurringInvoice.on('loaded', function(){
                loadView(recurringInvoice);
            });

            recurringInvoice.importInvoice(invoiceId);
        }
        else{

            $.when(recurringInvoice.loadDefaults()).done(function () {
                loadView(recurringInvoice);
            });
        }




    },
    estimate: function (id) {
        DUET.baseModelRoute('estimate', id);
    },
    file:function (id) {
        DUET.baseModelRoute('file', id);
    },
    dashboard:function () {
        var dashboardView,
            dashboard = new DUET.Dashboard();

        DUET.panelTwo.loading();

        //todo:this route is getting called before the initialization has completed, causing this if statement to be required. This shouldn't be necessary.
        if (DUET.initComplete == true) {
            DUET.panelTwo.setTitle(ut.lang('sidebar.dashboard'));
            DUET.panelTwo.setModel(dashboard);
            DUET.panelTwo.clearProjectItemCategories();
            DUET.panelOne.hide();
        }

        dashboard.on('loaded', function () {
            dashboardView = new DUET.DashboardView(dashboard);
            DUET.panelTwo.setContent(dashboardView);
        });

        dashboard.load(1);
    },
    login:function () {
        DUET.stop();
    },
    logout:function () {
        new DUET.Request({
            url:'app/logout',
            success:function () {
                window.location = '#login';
            }
        });
    },
    myProfile:function () {
        this.user(DUET.my.id);
        DUET.panelOne.hide();

    },
    search:function (query) {

        var searchModel = new DUET.Search();


        //DUET.context('search', 1);
        DUET.panelTwo.loading();
        DUET.panelTwo.setTitle('Search results for \'' + query + '\''); //todo:lang file
        DUET.panelTwo.setModel(searchModel);
        DUET.panelTwo.clearProjectItemCategories();
        DUET.panelOne.hide();

        searchModel.on('loaded', function () {
            var searchResultsView = new DUET.SearchResultsView(searchModel);

            DUET.panelTwo.setContent(searchResultsView);
        });

        searchModel.load(query);
    },
    forgotPassword:function () {
        var forgotPasswordView = new DUET.ForgotPasswordView();
        forgotPasswordView.addTo({$anchor:$('body')});
    },
    settings:function (tab) {
        if (!DUET.userIsAdmin())
            return false;

        DUET.panelTwo.loading();

        var settings = new DUET.Setting();

        DUET.panelTwo.setTitle(ut.lang('adminSettings.title'));
        DUET.panelTwo.setModel();
        DUET.panelOne.hide();
        DUET.panelTwo.clearProjectItemCategories();
        settings.on('loaded', function () {

            var settingsView = new DUET.SettingsView(settings, tab);
            DUET.panelTwo.setContent(settingsView);
        });

        settings.load(1);

    },
    reporting:function () {
        var reports = new DUET.Reports();

        DUET.panelTwo.loading();

        //DUET.context('reporting', 1);
        DUET.panelTwo.setTitle(ut.lang('reporting.title'));
        DUET.panelTwo.setModel();
        DUET.panelTwo.clearProjectItemCategories();
        DUET.panelOne.hide();


        reports.on('loaded', function () {
            var reportingView = new DUET.ReportingView(reports);
            DUET.panelTwo.setContent(reportingView);
        });

        reports.load(1);
    },
    discussion:function (referenceObjectPlural, referenceId) {
        var params = arguments, referenceObject, discussion;

        DUET.panelOne.hide();
        DUET.panelTwo.loading();

        if (params.length == 2) {
            referenceObjectPlural = params[0];
            referenceId = params[1];
        }
        else {
            referenceObjectPlural = params[1];
            referenceId = params[2];

        }

        referenceObject = referenceObjectPlural.slice(0, -1);

        discussion = new DUET.Discussion({
            referenceObject:referenceObject,
            referenceId:referenceId
        });

        DUET.context(referenceObject, referenceId);

        discussion.load(1);

        discussion.on('loaded', function () {
            var project = new DUET.Project();

            if(discussion.entity.type == 'project')
                project.load(discussion.entity);
            else project.load({id:discussion.entity.projectId});

            DUET.panelTwo.setModel(project);
            DUET.panelTwo.setTitle(DUET.getModelTitle(discussion.entity));
            var discussionView = new DUET.DiscussionView(discussion);
            DUET.panelTwo.setInnerContent(discussionView);


            DUET.panelTwo.buildProjectItemCategories('project-discussion');
        });
    }

};


//common functions used throughout the routes
DUET.routeHelpers = {
    initPrimaryPanel:function (projectType) {
        DUET.panelOne.setList(projectType);
    },
    initSecondaryPanel:function (params) {
        var collection, view, activeModel, activeModelSingular, activeModelName, project, params, projectId, activeModelId;

        //secondary panel
        projectId = params[0];
        activeModel = params[1] || DUET.options.defaultProjectTab;
        activeModelName = DUET.utils.ucFirst(activeModel);
        activeModelId = params[2];
        activeModelSingular = DUET.utils.trim(activeModel, 's');

        var project = params[2] !== 'template' ? new DUET.Project() : new DUET.Template();
        project.load(projectId);

        //todo: why is the slide out panel even showing? It needs to be smarter about when it opens
        DUET.panelOne.hide();

        return{
            activeModel:activeModel,
            activeModelName:activeModelName,
            activeModelId:activeModelId,
            activeModelSingular:activeModelSingular,
            project:project
        };
    },
    collectionHandler:function () {
    },
    panelTwoHandler:function (params, view) {
        var context;
        DUET.panelTwo.setInnerContent(view); //TODO: Think about having a DUET.setContent('panelTwo', view.get()), basically an app level set content function?
        DUET.panelTwo.buildProjectItemCategories('project-' + params.activeModel);

    }
};

DUET.getModelTitle = function (model) {
    var type = model.type,
        title = ' ';

    switch (type) {
        case 'project':
        case 'client':
        case 'file':
            title = model.name;
            break;
        case 'task':
            title = 'Task: ' + model.task.substr(0, 10) + '...';
            break;
        case 'invoice':
            title = 'Invoice ' + model.number;
            break;
        case 'user':
            title = model.firstName + ' ' + model.lastName;
            break;
        case 'dashboard':
            title = 'Dashboard';
            break;
        case 'recurringinvoice':
            title = 'Recurring Invoice';
            break;
    }

    return title;

};

DUET.baseModelRoute = function (modelType, id) {
    var model, view, modelData, modelTypeCapitalized;



    if(modelType == 'recurringinvoice')
        modelTypeCapitalized = 'RecurringInvoice';
    else modelTypeCapitalized = DUET.utils.ucFirst(modelType);


    if (!id)
        DUET.panelOne.setList(modelType);
    else {

        DUET.panelTwo.loading();

        DUET.panelOne.hide();
        DUET.panelTwo.clearProjectItemCategories();
        modelData = id;

        //secondary panel
        model = new DUET[modelTypeCapitalized];

        model.on('loaded', function () {
            //  DUET.context(modelType, model.id);
            if (DUET[modelTypeCapitalized + 'DetailsView'])
                view = new DUET[modelTypeCapitalized + 'DetailsView'](model);
            else view = new DUET[modelTypeCapitalized + 'View'](model);

            DUET.panelTwo.setTitle(DUET.getModelTitle(model));
            DUET.panelTwo.setContent(view);
            DUET.panelTwo.setModel(model);
        });

        model.load(modelData);
    }

};
