<?php

namespace Dorcas\ModulesAppStore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dorcas\ModulesAppStore\Models\ModulesAppStore;
use App\Dorcas\Hub\Utilities\UiResponse\UiResponse;
use App\Http\Controllers\HomeController;
use Hostville\Dorcas\Sdk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\RecordNotFoundException;

class ModulesAppStoreController extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->data = [
            'page' => ['title' => config('modules-app-store.title')],
            'header' => ['title' => config('modules-app-store.title')],
            'selectedMenu' => 'addons',
            'submenuConfig' => 'navigation-menu.addons.sub-menu.modules-app-store.sub-menu',
            'submenuAction' => ''
        ];
    }

    public function index(Request $request, Sdk $sdk)
    {
    	//$this->data['filter'] = 'installed_only';
        //$this->data['filter'] = 'without_installed';
        $this->data['authToken'] = $sdk->getAuthorizationToken();
    	return view('modules-app-store::index', $this->data);
    }

    /**
     * @param Request $request
     * @param Sdk     $sdk
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request, Sdk $sdk)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', '');
        $order = $request->query('order', 'asc');
        $offset = (int) $request->query('offset', 0);
        $page = (int) $request->query('page', 0);
        $limit = (int) $request->query('limit', 10);
        # get the request parameters
        $resource = $sdk->createAppStoreResource();
        $resource = $resource->addQueryArgument('limit', $limit)
                                ->addQueryArgument('page', $page);
        if (!empty($search)) {
            $resource->addQueryArgument('search', $search);
        }
        if ($request->has('filter')) {
            $resource->addQueryArgument('filter', $request->input('filter'));
        }
        if ($request->has('category_slug')) {
            $resource->addQueryArgument('category_slug', $request->input('category_slug'));
        }
        $response = $resource->send('get');
        # make the request
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find any matching applications in the app store.');
        }
        return response()->json(['data' => $response->getData(), 'meta' => $response->meta]);
    }
    
    /**
     * @param Request $request
     * @param Sdk     $sdk
     * @param string  $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function installApp(Request $request, Sdk $sdk, string $id)
    {
        $response = $sdk->createAppStoreResource($id)->send('post');
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Installation failed!');
        }
        return response()->json($response->getData());
    }
    
    /**
     * @param Request $request
     * @param Sdk     $sdk
     * @param string  $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uninstallApp(Request $request, Sdk $sdk, string $id)
    {
        $response = $sdk->createAppStoreResource($id)->send('delete');
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Uninstallation failed!');
        }
        return response()->json($response->getData());
    }

}