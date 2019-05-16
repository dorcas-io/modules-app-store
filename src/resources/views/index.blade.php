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
                         				<a href="@{{app.homepage_url}}" class="text-default" v-html="app.homepage_url"></a>
                         				<!-- <small class="d-block text-muted">3 days ago</small> -->
                         			</div>
                         			<div class="ml-auto text-muted">
                         				<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary d-none d-md-inline-block ml-3">Install</a>
                         			</div>
                         		</div>
                         	</div>
                         </div>

		            </div>
		            <div class="col s12" v-if="apps.length === 0">
		                @component('layouts.blocks.tabler.empty-card')
		                    You have no Apps installed
		                    @slot('buttons')
		                        <a href="#" v-on:click.prevent="createDepartment" class="btn btn-primary btn-sm">Explore App Store</a>
		                    @endslot
		                @endcomponent
		            </div>
		        </div>


	        </div>
	        <div class="tab-pane container" id="apps_apps-store">
	            <br/>
	            
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
                filter: '{{ $filter or 'all' }}',
                is_fetching: false,
                meta: [],
                page_number: 1,
                limit: 12,
                search_term: '',
                category_slug: '',
                authorization_token: '{{ $authToken or '' }}'
            },
            mounted: function () {
                this.searchAppStore();
            },
            methods: {
                changePage: function (number) {
                    this.page_number = parseInt(number, 10);
                    this.searchAppStore();
                },
                searchAppStore: function (page, limit) {
                    let context = this;
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
                            filter: context.filter
                        }
                    }).then(function (response) {
                        context.is_fetching = false;
                        context.apps = response.data.data;
                        context.meta = response.data.meta;
                    }).catch(function (error) {
                        var message = '';
                        context.is_fetching = false;
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            var e = error.response.data.errors[0];
                            message = e.title;
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
                installApp: function (index) {
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
                    swal({
                        title: "Install Application?",
                        text: "You are about to install an application " + app.name + ". Would you like to continue?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: "Continue.",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    }, function() {
                        context.is_fetching = true;
                        axios.post("/map/app-store/" + app.id).then(function (response) {
                            console.log(response);
                            context.is_fetching = false;
                            //Materialize.toast('Updated the settings for the ' + context.display_name + ' integration.', 4000);
                            Vue.set(context.apps[index], 'is_installed', true);
                            // set the app as installed
                            return swal("Installed!", "The application was successfully installed.", "success");
                        }).catch(function (error) {
                            let message = '';
                            if (error.response) {
                                // The request was made and the server responded with a status code
                                // that falls out of the range of 2xx
                                var e = error.response.data.errors[0];
                                message = e.title;
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
                    swal({
                        title: "Uninstall Application?",
                        text: "You are about to uninstall application " + app.name + ". This could result in loss of data.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, uninstall it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    }, function() {
                        context.is_fetching = true;
                        axios.delete("/map/app-store/" + app.id)
                            .then(function (response) {
                                console.log(response);
                                context.is_fetching = false;
                                Vue.set(context.apps[index], 'is_installed', false);
                                // set the app as installed
                                return swal("Uninstalled!", "The application was successfully uninstalled.", "success");
                            })
                            .catch(function (error) {
                                context.is_fetching = false;
                                var message = '';
                                if (error.response) {
                                    // The request was made and the server responded with a status code
                                    // that falls out of the range of 2xx
                                    var e = error.response.data.errors[0];
                                    message = e.title;
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
                    });
                }
            }
        });
    </script>
@endsection