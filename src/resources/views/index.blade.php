@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection
@section('body_content_main')
@include('layouts.blocks.tabler.alert')

<div class="row">
    @include('layouts.blocks.tabler.sub-menu')

    <div class="col-md-9 col-xl-9">
	    <ul class="nav nav-tabs nav-justified">
	        <li class="nav-item">
	            <a class="nav-link active" data-toggle="tab" href="#apps_my-apps">My Apps</a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" data-toggle="tab" href="#apps_apps-store">App Store</a>
	        </li>
	    </ul>

	    <div class="tab-content" id="listing_apps">
	        <div class="tab-pane container active" id="apps_my-apps">
	            <br/>
		        <div class="container" id="listing_myapps">
		            <div class="row mt-3" v-show="apps.length > 0">
                         <div class="card card-aside" v-for="(app, index) in apps" :key="app.id" :app="app" :index="index">
                         	<a href="#" class="card-aside-column" v-bind:style="{ 'background-image': 'url(' + app.icon_url + ')' }"></a>
                         	<div class="card-body d-flex flex-column">
                         		<h4>@{{ app.name }}</h4>
                         		<div class="text-muted">@{{ app.description }}</div>
                         		<div class="d-flex align-items-center pt-5 mt-auto">
                         			<div>
                         				<p>
                         					Website: <a v-bind:href="app.homepage_url" target="_blank" class="text-default" v-html="app.homepage_url"></a>
                         				</p>
                         				<!-- <small class="d-block text-muted">3 days ago</small> -->
                         				<a href="javascript:void(0)" v-on:click.prevent="uninstallApp(index)" class="btn btn-sm btn-outline-danger d-none d-md-inline-block ml-3">Uninstall</a>
                         			</div>
                         			<div class="ml-auto text-muted">
                         				<a href="javascript:void(0)" v-on:click.prevent="launchApp(index)" class="btn btn-sm btn-outline-success d-none d-md-inline-block ml-3">Launch</a>
                         			</div>
                         		</div>
                         	</div>
                         </div>
						<ul class="pagination justify-content-end" v-if="apps.length > 0 && !is_fetching && typeof meta.pagination !== 'undefined' && meta.pagination.total_pages > 1">
							<!--TODO: Handle situations where the number of pages > 10; we need to limit the pages displayed in those cases -->
							<li class="page-item"><a class="page-link" href="#!" v-on:click.prevent="changePage(1,'myapps')">First</a></li>
							<li class="page-item" v-for="n in meta.pagination.total_pages" v-bind:class="{active: n === page_number}">
							    <a class="page-link" href="#" v-on:click.prevent="changePage(n,'myapps')" v-if="n !== page_number">@{{ n }}</a>
							    <a class="page-link" href="#" v-else>@{{ n }}</a>
							</li>
							<li class="page-item"><a class="page-link" href="#!" v-on:click.prevent="changePage(meta.pagination.total_pages,'myapps')">Last</a></li>
						</ul>
		            </div>
		            <div class="col s12" v-if="apps.length === 0 && !is_fetching">
		                @component('layouts.blocks.tabler.empty-card')
		                    You have no Apps installed
		                    @slot('buttons')
		                        <a href="#" v-on:click.prevent="openTab('apps_apps-store')" class="btn btn-primary btn-sm">Explore App Store</a>
		                    @endslot
		                @endcomponent
		            </div>
		            <div class="col s12" v-if="apps.length === 0 && is_fetching">
                      <div class="loader"></div>
                      <div>Loading your Installed Apps</div>
		            </div>
		        </div>
	        </div>
	        <div class="tab-pane container" id="apps_apps-store">
	            <br/>
		        <div class="container" id="listing_appsstore">
		            <div class="row mt-3" v-show="apps2.length > 0">

                         <div class="card card-aside" v-for="(app, index) in apps2" :key="app.id" :app="app" :index="index">
                         	<a href="#" class="card-aside-column" v-bind:style="{ 'background-image': 'url(' + app.icon_url + ')' }"></a>
                         	<div class="card-body d-flex flex-column">
                         		<h4>@{{ app.name }}</h4>
                         		<div class="text-muted">@{{ app.description }}</div>
                         		<div class="d-flex align-items-center pt-5 mt-auto">
                         			<div>
                         				<a v-bind:href="app.homepage_url" class="text-default" v-html="app.homepage_url"></a>
                         				<!-- <small class="d-block text-muted">3 days ago</small> -->
                         			</div>
                         			<div class="ml-auto text-muted">
                         				<a href="#" v-on:click.prevent="installApp(index)" class="btn btn-sm btn-outline-primary d-none d-md-inline-block ml-3">Install</a>
                         			</div>
                         		</div>
                         	</div>
                         </div>
        						<ul class="pagination justify-content-end" v-if="apps2.length > 0 && !is_fetching2 && typeof meta2.pagination !== 'undefined' && meta2.pagination.total_pages > 1">
        							<!--TODO: Handle situations where the number of pages > 10; we need to limit the pages displayed in those cases -->
        							<li class="page-item"><a class="page-link" href="#!" v-on:click.prevent="changePage(1,'apps')">First</a></li>
        							<li class="page-item" v-for="n in meta2.pagination.total_pages" v-bind:class="{active: n === page_number2}">
        							    <a class="page-link" href="#" v-on:click.prevent="changePage(n,'apps')" v-if="n !== page_number2">@{{ n }}</a>
        							    <a class="page-link" href="#" v-else>@{{ n }}</a>
        							</li>
        							<li class="page-item"><a class="page-link" href="#!" v-on:click.prevent="changePage(meta2.pagination.total_pages,'apps')">Last</a></li>
        						</ul>
		            </div>
		            <div class="col s12" v-if="apps2.length === 0 && !is_fetching">
		                @component('layouts.blocks.tabler.empty-card')
		                    It appears you have installed all Apps in the Apps Store!
		                    @slot('buttons')
		                        
		                    @endslot
		                @endcomponent
		            </div>
		            <div class="col s12" v-if="apps.length === 0 && is_fetching">
                      <div class="loader"></div>
                      <div>Loading Apps</div>
		            </div>
		        </div>
	        </div>
	    </div>

    </div>

</div>


@endsection
@section('body_js')
    <script type="text/javascript">
        new Vue({
            el: '#listing_apps',
            data: {
                apps: [],
                filter: '{{ $filter or 'installed_only' }}',
                is_fetching: false,
                meta: [],
                page_number: 1,
                limit: 12,
                search_term: '',
                category_slug: '',
                authorization_token: '{{ $authToken or '' }}',
                apps2: [],
                filter2: '{{ $filter or 'without_installed' }}',
                is_fetching2: false,
                meta2: [],
                page_number2: 1,
                limit2: 12,
                search_term2: '',
                category_slug2: ''
            },
            mounted: function () {
                this.searchAppStore(this.page_number, this.limit, 'installed_only');
                this.searchAppStore(this.page_number2, this.limit, 'without_installed');
                this.openTabOnLoad();
            },
            methods: {
            	openTab: function (tab) {
            		$('.nav-tabs a[href="#' + tab + '"]').tab('show');
            	},
            	openTabOnLoad: function() {
					var url = document.location.toString();
					if (url.match('#')) {
					    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
					}
            	},
                changePage: function (number,tab) {
                	if (tab=='my_apps') {
                    	this.page_number = parseInt(number, 10);
                    	this.searchAppStore(this.page_number, this.limit, 'installed_only');
                	} else if (tab=='apps') {
                    	this.page_number2 = parseInt(number, 10);
                    	this.searchAppStore(this.page_number2, this.limit2, 'without_installed');
                	}
                },
                searchAppStore: function (page, limit, filt) {
                    let context = this;
                    if (typeof filt !== 'undefined') {
                    	this.filter = filt;
                    }
                    if (typeof page !== 'undefined' && !isNaN(page)) {
                        this.page_number = page;
                    }
                    if (typeof limit !== 'undefined' && !isNaN(limit)) {
                        this.limit = limit;
                    }
                    this.is_fetching = true;
                    axios.get("/map/app-store", {
                        params: {
                            search: context.search_term,
                            limit: context.limit,
                            page: context.page_number,
                            category_slug: context.category_slug,
                            filter: filt
                        }
                    }).then(function (response) {
                        if (filt == 'installed_only') {
                        	context.is_fetching = false;
                        	context.apps = response.data.data;
                        	context.meta = response.data.meta;
                        } else if (filt == 'without_installed') {
                        	context.is_fetching2 = false;
                        	context.apps2 = response.data.data;
                        	context.meta2 = response.data.meta;
                        }
                    }).catch(function (error) {
                        var message = '';
                        context.is_fetching = false;
                        console.log(error.response)
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            //var e = error.response.data.errors[0];
                            //message = e.title;
                            var e = error.response;
                            message = e.data.message;
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            message = 'The request was made but no response was received';
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            message = error.message;
                        }
                        return swal("Oops!", message, "warning");
                    });
                },
                launchApp: function(index) {
                    let context = this;
                    if (this.is_fetching) {
                        // currently processing something
                        swal('Please Wait', 'Your previous request is still processing...', 'info');
                        return;
                    }
                    let app = typeof this.apps[index] !== 'undefined' ? this.apps[index] : {};
                    if (typeof app.id === 'undefined') {
                        return;
                    }
                	if (app.is_installed && app.homepage_url !== null && app.type === 'web') {
                		let launch_url = app.homepage_url + '/install/setup?token=' + context.authorization_token;
                		window.open(launch_url);
                	}
                },
                installApp: function (index) {
                    let context = this;
                    if (this.is_fetching) {
                        // currently processing something
                        swal('Please Wait', 'Your previous request is still processing...', 'info');
                        return;
                    }
                    let app = typeof this.apps2[index] !== 'undefined' ? this.apps2[index] : {};
                    if (typeof app.id === 'undefined') {
                        return;
                    }
                    Swal.fire({
                        title: "Install Application?",
                        text: "You are about to install an application " + app.name + ". Would you like to continue?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: "Continue.",
                        showLoaderOnConfirm: true,
		                preConfirm: (install_app) => {

	                        context.is_fetching = true;
	                        return axios.post("/map/app-store/" + app.id)
	                        .then(function (response) {
	                            console.log(response);
	                            context.is_fetching = false;
	                            //Materialize.toast('Updated the settings for the ' + context.display_name + ' integration.', 4000);
	                            //Vue.set(context.apps[index], 'is_installed', true);
	                            // set the app as installed
	                            window.location = '{{ url()->current() }}';
	                            context.apps2.splice(index, 1);
	                            return swal("Installed!", "The application was successfully installed.", "success");
	                        }).catch(function (error) {
	                            let message = '';
	                            if (error.response) {
	                            	console.log(error.response)
	                                // The request was made and the server responded with a status code
	                                // that falls out of the range of 2xx
	                                //var e = error.response.data.errors[0];
	                                //message = e.title;
		                            var e = error.response;
		                            message = e.data.message;
	                            } else if (error.request) {
	                                // The request was made but no response was received
	                                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
	                                // http.ClientRequest in node.js
	                                message = 'The request was made but no response was received';
	                            } else {
	                                // Something happened in setting up the request that triggered an Error
	                                message = error.message;
	                            }
	                            context.is_fetching = false;
	                            //Materialize.toast("Oops!" + message, 4000);
	                            return swal("Install Failed", message, "warning");
	                        });



		                },
		                allowOutsideClick: () => !Swal.isLoading()

                    });
                },
                uninstallApp: function (index) {
                    let context = this;
                    if (this.is_fetching) {
                        // currently processing something
                        swal('Please Wait', 'Your previous request is still processing...', 'info');
                        return;
                    }
                    let app = typeof this.apps[index] !== 'undefined' ? this.apps[index] : {};
                    if (typeof app.id === 'undefined') {
                        return;
                    }
                    Swal.fire({
                        title: "Uninstall Application?",
                        text: "You are about to uninstall application " + app.name + ". This could result in loss of data.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, uninstall it!",
                        showLoaderOnConfirm: true,
		                preConfirm: (uninstall_app) => {
	                        context.is_fetching = true;
	                        return axios.delete("/map/app-store/" + app.id)
	                            .then(function (response) {
	                                console.log(response);
	                                context.is_fetching = false;
	                                //Vue.set(context.apps[index], 'is_installed', false);
	                                // set the app as installed
	                                window.location = '{{ url()->current() }}';
	                                context.apps.splice(index, 1);
	                                return swal("Uninstalled!", "The application was successfully uninstalled.", "success");
	                            })
	                            .catch(function (error) {
	                                context.is_fetching = false;
	                                var message = '';
	                                if (error.response) {
	                                    // The request was made and the server responded with a status code
	                                    // that falls out of the range of 2xx
	                                    //var e = error.response.data.errors[0];
	                                    //message = e.title;
			                            var e = error.response;
			                            message = e.data.message;
	                                } else if (error.request) {
	                                    // The request was made but no response was received
	                                    // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
	                                    // http.ClientRequest in node.js
	                                    message = 'The request was made but no response was received';
	                                } else {
	                                    // Something happened in setting up the request that triggered an Error
	                                    message = error.message;
	                                }
	                                return swal("Uninstall Failed", message, "warning");
	                            });

		                },
		                allowOutsideClick: () => !Swal.isLoading()
                    });
                }
            }
        });
    </script>
@endsection