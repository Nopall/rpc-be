<?php

namespace App\Http\Controllers;

use App\Exports\exportValue;
use App\Imports\UsersImport;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/exportlocation",
     * operationId="export Location",
     * tags={"Location"},
     * summary="export Location excel",
     * description="export Location excel",
     *   @OA\Response(
     *          response=201,
     *          description="Generate Excel Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Generate Excel Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function exportLocation(Request $request)
    {

        return Excel::download(new exportValue, 'Location.xlsx');

    }

    /**
     * @OA\Delete(
     * path="/api/location",
     * operationId="delete location",
     * tags={"Location"},
     * summary="Delete Location",
     * description="Delete Location , by delete location will update status isDeleted into 1)",
     *     @OA\RequestBody(
     *         @OA\JsonContent(* @OA\Examples(
     *        summary="Delete Location",
     *        example = "Delete Location",
     *        value = {
     *           "codeLocation":"abc123",
     *         },)),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="codeLocation", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Delete location Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Delete location Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function deleteLocation(Request $request)
    {

        $request->validate([
            'codeLocation' => 'required',
        ]);

        DB::beginTransaction();
        try
        {

            DB::table('location')
                ->where('codeLocation', '=', $request->input('codeLocation'))
                ->update([
                    'isDeleted' => 1,
                ]);

            DB::table('location_detail_address')
                ->where('codeLocation', '=', $request->input('codeLocation'))
                ->update([
                    'isDeleted' => 1,
                ]);

            DB::table('location_email')
                ->where('codeLocation', '=', $request->input('codeLocation'))
                ->update([
                    'isDeleted' => 1,
                ]);

            DB::table('location_messenger')
                ->where('codeLocation', '=', $request->input('codeLocation'))
                ->update([
                    'isDeleted' => 1,
                ]);

            DB::table('location_telephone')
                ->where('codeLocation', '=', $request->input('codeLocation'))
                ->update([
                    'isDeleted' => 1,
                ]);

            DB::commit();

            return response()->json([
                'result' => 'success',
            ]);

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'result' => 'failed',
                'token' =>  $e,
            ]);
         
        }

    }

    public function uploadexceltest(Request $request)
    {

        $request->validate([
            'file' => 'required|max:10000',
        ]);

        Excel::import(new UsersImport, $request->file);

        return response()->json([
            'result' => 'success',
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/location",
     * operationId="Update Location",
     * tags={"Location"},
     * summary="Update Location",
     * description="Update Location",
     *     @OA\RequestBody(
     *         @OA\JsonContent(* @OA\Examples(
     *        summary="update Location",
     *        example = "update Location",
    * value = 
    *{
    *  "id": 1,
    *  "codeLocation": "abc123",
    *  "locationName": "RPC Permata Hijau Pekanbaru",
    *  "isBranch": 0,
    *  "status": 1,
    *  "description": "Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum fuga, alias placeat necessitatibus dolorem ea autem tempore omnis asperiores nostrum, excepturi a unde mollitia blanditiis iusto. Dolorum tempora enim atque.",
    *  "image": "D:\\ImageFolder\\ExamplePath\\ImageRPCPermataHijau.jpg",
    *  "imageTitle": "ImageRPCPermataHijau.jpg",
    *  "detailAddress": {
    *    {
    *      "id": 1,
    *      "addressName": "Jalan U 27 B Palmerah Barat no 206 Jakarta Barat 11480",
    *      "additionalInfo": "Didepan nasi goreng kuning arema, disebelah bubur pasudan",
    *      "provinceCode": 12,
    *      "cityCode": 1102,
    *      "postalCode": 9999,
    *      "country": "Indonesia",
    *      "isPrimary": 1
    *    }
    *  },
    *  "operationalHour": {
    *    {
    *      "id": 1,
    *      "dayName": "Monday",
    *      "fromTime": "10:00PM",
    *      "toTime": "10:00PM",
    *      "allDay": 1
    *    },
    *    {
    *      "id": 2,
    *      "dayName": "Tuesday",
    *      "fromTime": "12:00PM",
    *      "toTime": "13:00PM",
    *      "allDay": 1
    *    },
    *    {
    *      "id": 3,
    *      "dayName": "Wednesday",
    *      "fromTime": "10:00PM",
    *      "toTime": "10:00PM",
    *      "allDay": 1
    *    },
    *    {
    *      "id": 4,
    *      "dayName": "Thursday",
    *      "fromTime": "10:00PM",
    *      "toTime": "10:00PM",
    *      "allDay": 1
    *    },
    *    {
    *      "id": 5,
    *      "dayName": "Friday",
    *      "fromTime": "10:00PM",
    *      "toTime": "10:00PM",
    *      "allDay": 1
    *    }
    *  },
    *  "messenger": {
    *    {
    *      "id": 1,
    *      "messengerNumber": "(021) 3851185",
    *      "type": "Fax",
    *      "usage": "Utama"
    *    },
    *    {
    *      "id": 2,
    *      "messengerNumber": "(021) 012345678",
    *      "type": "Office",
    *      "usage": "Utama"
    *    }
    *  },
    *  "email": {
    *    {
    *      "id": 1,
    *      "username": "wahyudidanny23@gmail.com",
    *      "type": "Personal",
    *      "usage": "Utama"
    *    },
    *    {
    *      "id": 2,
    *      "username": "wahyudidanny25@gmail.com",
    *      "type": "Personal",
    *      "usage": "Secondary"
    *    }
    *  },
    *  "telephone": {
    *    {
    *      "id": 1,
    *      "phoneNumber": "087888821648",
    *      "type": "Telepon Selular",
    *      "usage": "Utama"
    *    },
    *    {
    *      "id": 2,
    *      "phoneNumber": "085265779499",
    *      "type": "Whatshapp",
    *      "usage": "Secondary"
    *    }
    *  }
    *}  
     *          )),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"locationName","isBranch","password"},
     *               @OA\Property(property="name", type="text"),

     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Update Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Update Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function updateLocation(Request $request)
    {

        DB::beginTransaction();
        try
        {

            DB::table('location')
                ->where('codeLocation', '=', $request->input('codeLocation'))
                ->update(['locationName' => $request->input('locationName'),
                            'isBranch' => $request->input('isBranch'),
                            'status' => $request->input('status'),
                            'description' => $request->input('description'),
                            'image' => $request->input('image'),
                            'imageTitle' => $request->input('imageTitle'),
                            'updated_at' => now(),
                        ]);

            foreach ($request->detailAddress as $val) {
                
                if(isset($val['codeLocation'])) 
                {
                    if(isset($val['isDeleted'])){
                    
                        DB::table('location_detail_address')
                        ->where('codeLocation', '=', $val['codeLocation'],
                                 'id', '=', $val['id'],)
                       ->update(['addressName' => $val['addressName'],
                                'additionalInfo' => $val['additionalInfo'],
                                'provinceCode' => $val['provinceCode'],
                                'cityCode' => $val['cityCode'],
                                'postalCode' => $val['postalCode'],
                                'country' => $val['country'],
                                'isPrimary' => $val['isPrimary'],
                                'isDeleted' => $val('isDeleted'),
                                'updated_at' => now(),
                            ]);

                    }else{

                        DB::table('location_detail_address')
                        ->where('codeLocation', '=', $val['codeLocation'],
                                'id', '=', $val['id'],)
                        ->update(['addressName' => $val['addressName'],
                                'additionalInfo' => $val['additionalInfo'],
                                'provinceCode' => $val['provinceCode'],
                                'cityCode' => $val['cityCode'],
                                'postalCode' => $val['postalCode'],
                                'country' => $val['country'],
                                'isPrimary' => $val['isPrimary'],
                                'updated_at' => now(),
                            ]);

                    }

                }else{

                    DB::table('location_detail_address')
                    ->insert(['codeLocation' => $request->input('codeLocation'),
                              'addressName' => $val['addressName'],
                              'additionalInfo' => $val['additionalInfo'],
                              'provinceCode' => $val['provinceCode'],
                              'cityCode' => $val['cityCode'],
                              'postalCode' => $val['postalCode'],
                              'country' => $val['country'],
                              'isPrimary' => $val['isPrimary'],
                              'isDeleted' => 0,
                              'created_at' => now()
                            ]);
                }



            }



            foreach ($request->operationalHour as $val) {
   
                if(isset($val['codeLocation'])) 
                {
                    if(isset($val['isDeleted'])){
                    
                            DB::table('location_operational')
                            ->where('codeLocation', '=', $val['codeLocation'],
                                    'id', '=', $val['id'],)
                            ->update([
                                    'dayName' => $val['dayName'],
                                    'fromTime' => $val['fromTime'],
                                    'toTime' => $val['toTime'],
                                    'allDay' => $val['allDay'],
                                    'isDeleted' => $val('isDeleted'),
                                    'updated_at' => now(),
                            ]);


                    }else{

                        DB::table('location_operational')
                        ->where('codeLocation', '=', $val['codeLocation'],
                                'id', '=', $val['id'],)
                        ->update([
                            'dayName' => $val['dayName'],
                            'fromTime' => $val['fromTime'],
                            'toTime' => $val['toTime'],
                            'allDay' => $val['allDay'],
                            'updated_at' => now(),
                        ]);

                    }

                }else{

                    DB::table('location_operational')
                    ->insert(['codeLocation' => $request->input('codeLocation'),
                              'dayName' => $val['dayName'],
                              'fromTime' => $val['fromTime'],
                              'toTime' => $val['toTime'],
                              'allDay' => $val['allDay'],
                            ]);
                  }           
            }

            foreach ($request->messenger as $val) {
           

                    if(isset($val['codeLocation'])) 
                    {
                        if(isset($val['isDeleted'])){
                        
                                DB::table('location_messenger')
                                ->where('codeLocation', '=', $val['codeLocation'],
                                        'id', '=', $val['id'],)
                                ->update([
                                            'messengerName' => $val['messengerName'],
                                            'type' => $val['type'],
                                            'usage' => $val['usage'],
                                            'isDeleted' => $val('isDeleted'),
                                            'updated_at' => now(),
                                    ]);
            
                        }else{

                            DB::table('location_messenger')
                            ->where('codeLocation', '=', $val['codeLocation'],
                                    'id', '=', $val['id'],)
                            ->update([
                                        'messengerName' => $val['messengerName'],
                                        'type' => $val['type'],
                                        'usage' => $val['usage'],
                                        'updated_at' => now(),
                                ]);

                        }
    
                    }else{
    
                        DB::table('location_messenger')
                        ->insert(['codeLocation' => $request->input('codeLocation'),
                                   'messengerNumber' => $val['messengerNumber'],
                                   'type' => $val['type'],
                                   'usage' => $val['usage'],
                                   'isDeleted' => 0,
                                   'created_at' => now(),
                                ]);
                      }     

            }

            foreach ($request->email as $val) {

                if(isset($val['codeLocation'])) 
                {
                    if(isset($val['isDeleted'])){
                    
                        DB::table('location_email')
                            ->where('codeLocation', '=', $val['codeLocation'])
                            ->update([
                                    'username' => $val['username'],
                                    'usage' => $val['usage'],
                                    'type' => $val['type'],
                                    'isDeleted' => $val('isDeleted'),
                                    'updated_at' => now(),
                                ]);

        
                    }else{

                        DB::table('location_email')
                        ->where('codeLocation', '=', $val['codeLocation'])
                        ->update([
                                'username' => $val['username'],
                                'usage' => $val['usage'],
                                'type' => $val['type'],
                                'updated_at' => now(),
                            ]);

                    }

                }else{

                    DB::table('location_email')
                    ->insert(['codeLocation' => $request->input('codeLocation'),
                               'username' => $val['username'],
                               'type' => $val['type'],
                               'usage' => $val['usage'],
                               'isDeleted' => 0,
                               'created_at' => now(),
                             ]);
   
                  }   

            }

            foreach ($request->telephone as $val) {


                if(isset($val['codeLocation'])) 
                {
                    if(isset($val['isDeleted'])){
                  
                        DB::table('location_telephone')
                        ->where('codeLocation', '=', $val['codeLocation'],
                                'id', '=', $val['id'])
                        ->update(['usage' => $val['usage'],
                                    'phoneNumber' => $val['phoneNumber'],
                                    'type' => $val['type'],
                                    'isDeleted' => $val('isDeleted'),
                                    'updated_at' => now(),
                            ]);          
        
                    }else{

                        DB::table('location_telephone')
                            ->where('codeLocation', '=', $val['codeLocation'],
                                    'id', '=', $val['id'])
                            ->update(['usage' => $val['usage'],
                                     'phoneNumber' => $val['phoneNumber'],
                                     'type' => $val['type'],
                                     'updated_at' => now(),
                                ]);

                    }

                }else{

                    DB::table('location_telephone')
                    ->insert([ 'codeLocation' =>  $request->input('codeLocation'),
                               'phoneNumber' => $val['phoneNumber'],
                               'type' => $val['type'],
                               'usage' => $val['usage'],
                               'isDeleted' => 0,
                               'created_at' => now(),
                            ]);
   
                  }   

            }
            
            DB::commit();

            return response()->json([
                'result' => 'success',
            ]);

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'result' => 'failed',
                'token' =>  $e,
            ]);

        }

    }

    /**
     * @OA\Post(
     * path="/api/location",
     * operationId="Insert Location",
     * tags={"Location"},
     * summary="Insert Location",
     * description="Insert Location",
     *     @OA\RequestBody(
     *         @OA\JsonContent(* @OA\Examples(
     *        summary="Insert Location",
     *        example = "Insert Location",
     * value = 
    * {
    *        "locationName": "RPC Permata Hijau Jakarta",
    *        "isBranch": 0,
    *        "status": 1,
    *        "description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum fuga, alias placeat necessitatibus dolorem ea autem   tempore omnis asperiores nostrum, excepturi a unde mollitia blanditiis iusto. Dolorum tempora enim atque.",
    *        "image":"D:\\ImageFolder\\ExamplePath\\ImageRPCPermataHijau.jpg",
    *        "imageTitle":"ImageRPCPermataHijau.jpg",
    *        "detailAddress":{
    *                             {
    *                                "addressName": "Jalan U 27 B Palmerah Barat no 206 Jakarta Barat 11480",
    *                                "additionalInfo": "Didepan nasi goreng kuning arema, disebelah bubur pasudan",
    *                                "provinceCode":11,
    *                                "cityCode": 1101,
    *                                "postalCode": 9999,
    *                                "country": "Indonesia",
    *                                "isPrimary" : 1
    *                            }, 
    *                        {
    *                            "addressName": "Jalan Keluarga sebelah binus syahdan",
    *                            "additionalInfo": "Didepan nasi goreng kuning arema, disebelah bubur pasudan",
    *                            "provinceCode":12,
    *                            "cityCode": 1201,
    *                            "postalCode": 9999,
    *                            "country": "Indonesia",
    *                            "isPrimary" : 0
    *                        }
    *            },
    *            
    *        "operationalHour": {
    *                                {
    *                                    "dayName": "Monday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                }, 
    *                                {
    *                                    "dayName": "Tuesday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                },
    *                                {
    *                                    "dayName": "Wednesday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                },
    *                                {
    *                                    "dayName": "Thursday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                },
    *                                {
    *                                    "dayName": "Friday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                },
    *                                {
    *                                     "dayName": "Saturday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                },
    *                                {
    *                                    "dayName": "Sunday",
    *                                    "fromTime": "",
    *                                    "toTime": "",
    *                                    "allDay": 1
    *                                }
    *                            },
    *        "messenger":{
    *                        {
    *                            
    *                            "messengerNumber":"(021) 3851185",
    *							"type":"Fax",
    *							"usage":"Utama"
    *                            
    *                        },
    *                        {
    *
    *                            "messengerNumber":"(021) 012345678",
    *                            "type":"Office",
    *							"usage":"Secondary"
    *                        }
    *                    },
    *        "email":{	
    *                    {
    *                        
    *                        "username":"wahyudidanny23@gmail.com",
    *                        "type":"Personal",
    *						"usage":"Utama"
    *                    }, 
    *                    {
    *                       
    *                        "username":"wahyudidanny25@gmail.com",
    *						"type":"Secondary",
    *                        "usage":"Personal"
    *                    }
    *                },
    *        "telephone":{
    *                    {
    *                      
    *                        "phoneNumber":"087888821648",
    *                        "type":"Telepon Selular",
    *						"usage":"Utama"
    *                    }, 
    *                    {
    *                        
    *                        "phoneNumber":"085265779499",
    *                        "type":"Whatshapp",
    *						"usage":"Secondary"
    *                    }
    *                }
    *        }   
     *          )),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"locationName","isBranch","password"},
     *               @OA\Property(property="name", type="text"),

     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function insertLocation(Request $request)
    {
        DB::beginTransaction();

        try
        {

            $getvaluesp = strval(collect(DB::select('call generate_codeLocation'))[0]->randomString);

            $request->validate(['locationName' => 'required|max:255',
                                'isBranch' => 'required',
                                'status' => 'required',
                                'description' => 'required',
                                'image' => 'required',
                                 ]);

            DB::table('location')->insert(['codeLocation' => $getvaluesp,
                                           'locationName' => $request->input('locationName'),
                                           'isBranch' => $request->input('isBranch'),
                                           'status' => $request->input('status'),
                                           'description' => $request->input('description'),
                                           'image' => $request->input('image'),
                                           'imageTitle' => $request->input('imageTitle'),
                                           'isDeleted' => 0,
                                           'created_at' => now()
                                          ]);

            foreach ($request->detailAddress as $val) {
                DB::table('location_detail_address')
                ->insert(['codeLocation' => $getvaluesp,
                          'addressName' => $val['addressName'],
                          'additionalInfo' => $val['additionalInfo'],
                          'provinceCode' => $val['provinceCode'],
                          'cityCode' => $val['cityCode'],
                          'postalCode' => $val['postalCode'],
                          'country' => $val['country'],
                          'isPrimary' => $val['isPrimary'],
                          'isDeleted' => 0,
                          'created_at' => now()
                        ]);
            }

            foreach ($request->operationalHour as $val) {

                DB::table('location_operational')
                ->insert(['codeLocation' => $getvaluesp,
                          'dayName' => $val['dayName'],
                          'fromTime' => $val['fromTime'],
                          'toTime' => $val['toTime'],
                          'allDay' => $val['allDay'],
                        ]);
            }

            foreach ($request->messenger as $val) {

                DB::table('location_messenger')
                 ->insert(['codeLocation' => $getvaluesp,
                            'messengerNumber' => $val['messengerNumber'],
                            'type' => $val['type'],
                            'usage' => $val['usage'],
                            'isDeleted' => 0,
                            'created_at' => now(),
                         ]);
            }


            foreach ($request->email as $val) {

                DB::table('location_email')
                 ->insert(['codeLocation' => $getvaluesp,
                            'username' => $val['username'],
                            'type' => $val['type'],
                            'usage' => $val['usage'],
                            'isDeleted' => 0,
                            'created_at' => now(),
                          ]);

            }


            foreach ($request->telephone as $val) {

                DB::table('location_telephone')
                 ->insert([ 'codeLocation' => $getvaluesp,
                            'phoneNumber' => $val['phoneNumber'],
                            'type' => $val['type'],
                            'usage' => $val['usage'],
                            'isDeleted' => 0,
                            'created_at' => now(),
                         ]);

            }

            DB::commit();

            return response()->json([
                'result' => 'success',
            ]);

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'result' => 'failed',
                'token' =>  $e,
            ]);
        }

    }

    /**
     * @OA\Get(
     * path="/api/location/{request}",
     * operationId="Get Location Header",
     * tags={"Location"},
     * summary="Get Location Header Menu",
     * description="get Location Header Menu",
    * @OA\Parameter(
    *      in="path",
    *      name="request",
    *     @OA\JsonContent(
    *        type="object",
    *        @OA\Property(property="rowPerPage", type="number" , example="1"),
    *        @OA\Property(property="goToPage", type="number", example="11"),
    *        @OA\Property(property="orderValue", type="string" , example="asc"),
    *        @OA\Property(property="orderColumn", type="string", example="locationName"),
    *        @OA\Property(property="search", type="string" , example=""),
    *     ),
    * ),
     *   @OA\Response(
     *          response=201,
     *          description="Get Data Location Header Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Data Location Header Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function getLocationHeader(Request $request)
    {

        // $requestData = json_decode($request, true);
       
        $defaultRowPerPage = 5;

        $data = DB::table('location')
               ->leftjoin('location_detail_address', 'location_detail_address.codeLocation', '=', 'location.codeLocation')
               ->leftjoin('location_telephone', 'location_telephone.codeLocation', '=', 'location.codeLocation')
               ->leftjoin('kabupaten', 'kabupaten.kodeKabupaten', '=', 'location_detail_address.cityCode')
               ->select('location.id as id',
                        'location.codeLocation as codeLocation',
                        'location.locationName as locationName',
                        'location_detail_address.addressName as addressName',
                        'kabupaten.namaKabupaten as cityName',
                DB::raw("CONCAT(location_telephone.phoneNumber ,' ', location_telephone.usage) as phoneNumber"),
                        'location.status as status', )
              ->where([['location_detail_address.isPrimary', '=', '1'],
                       ['location_telephone.usage', '=', 'utama'],
                       ['location.isDeleted', '=', '0'],
                      ]);

         if ($request->search) {

            $data = $data->where('location.codeLocation', 'like', '%' . $request->search . '%')
                        ->orwhere('location.locationName', 'like', '%' . $request->search . '%')
                        ->orwhere('location_detail_address.addressName', 'like', '%' . $request->search . '%')
                        ->orwhere('kabupaten.namaKabupaten', 'like', '%' . $request->search . '%');

        }

        if ($request->orderColumn && $request->orderValue) {
            $data = $data->orderBy($request->orderColumn, $request->orderValue);
      
        }

        if ($request->rowPerPage > 0) {
            $rowPerPage = $request->rowPerPage;
    
        }

        $goToPage = $request->goToPage;

        $offset = ($goToPage - 1) * $defaultRowPerPage;

        $count_data = $data->count();
        $count_result = $count_data - $offset;

        if ($count_result < 0) {
            $data = $data->offset(0)->limit($defaultRowPerPage)->get();
        } else {
            $data = $data->offset($offset)->limit($defaultRowPerPage)->get();
        }

        $total_paging = $count_data / $defaultRowPerPage;
        return response()->json(['totalData' => ceil($total_paging), 'data' => $data], 200);

    }


     
	 /**
     * @OA\Get (
     * path="/api/detaillocation/{codeLocation}",
     * operationId="Get Location Detail",
     * tags={"Location"},
     * summary="Get Location Location Detail",
     * description="get Location Location Detail",
     *     @OA\Parameter(
     *         in="path",
     *         name="codeLocation",
     *         @OA\Schema(type="string",example="ABC123")
     *     ),
     *   @OA\Response(
     *          response=201,
     *          description="Get Data Location Detail Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Data Location Detail Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function getLocationDetail(Request $request)
    {
        $request->validate(['codeLocation' => 'required|max:10000']);
        $codeLocation = $request->input('codeLocation');

        $param_location = DB::table('location')
                            ->select('location.id as id',
                                    'location.codeLocation as codeLocation',
                                    'location.locationName as locationName',
                                    'location.isBranch as isBranch',
                                    'location.status as status',
                                    'location.description as description',
                                    'location.image as image',
                                    'location.imageTitle as imageTitle',)
                            ->where('location.codeLocation', '=', $codeLocation)
                            ->first();
        
                          
        $location_detail_address = DB::table('location_detail_address')
                                    ->select('location_detail_address.id as id',
                                            'location_detail_address.addressName as addressName',
                                            'location_detail_address.additionalInfo as additionalInfo',
                                            'location_detail_address.provinceCode as provinceCode',
                                            'location_detail_address.cityCode as cityCode',
                                            'location_detail_address.postalCode as postalCode',
                                            'location_detail_address.country as country',
                                            'location_detail_address.isPrimary as isPrimary',)
                                     ->where('location_detail_address.codeLocation', '=', $codeLocation)
                                     ->get();

        $param_location->detailAddress = $location_detail_address;

        $operationalHour = DB::table('location_operational')
            ->select('location_operational.id as id',
                    'location_operational.dayName as dayName',
                    'location_operational.fromTime as fromTime',
                    'location_operational.toTime as toTime',
                    'location_operational.allDay as allDay',)
            ->where('location_operational.codeLocation', '=', $codeLocation)
            ->get();

        $param_location->operationalHour = $operationalHour;

        $messenger_location = DB::table('location_messenger')
            ->select('location_messenger.id as id',
                    'location_messenger.messengerNumber as messengerNumber',
                    'location_messenger.type as type',
                    'location_messenger.usage as usage', )
            ->where('location_messenger.codeLocation', '=', $codeLocation)
            ->get();

        $param_location->messenger = $messenger_location;

        $email_location = DB::table('location_email')
            ->select('location_email.id as id',
                     'location_email.username as username',
                     'location_email.type as type',
                     'location_email.usage as usage', )
             ->where('location_email.codeLocation', '=', $codeLocation)
             ->get();

         $param_location->email = $email_location;

        $telepon_location = DB::table('location_telephone')
            ->select('location_telephone.id as id',
                     'location_telephone.phoneNumber as phoneNumber',
                     'location_telephone.type as type',
                     'location_telephone.usage as usage', )
             ->where('location_telephone.codeLocation', '=', $codeLocation)
             ->get();

        $param_location->telephone = $telepon_location;

        return response()->json($param_location, 200);
    }



    /**
     * @OA\Get(
     * path="/api/datastaticlocation",
     * operationId="get data static Location",
     * tags={"Location"},
     * summary="Get Data Static",
     * description="Get Data Static",
     *   @OA\Response(
     *          response=201,
     *          description="Get Data Static Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Data Static Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function getDataStaticLocation(Request $request)
    {

        try
        {
            
            $param_location = [];

            $data_static_telepon = DB::table('data_static')
                                    ->select('data_static.value as value',
                                             'data_static.name as name', )
                                    ->where('data_static.value', '=', 'Telephone')
                                    ->get();
    
            $data_static_messenger = DB::table('data_static')
                                      ->select('data_static.value as value',
                                               'data_static.name as name',)
                                      ->where('data_static.value', '=', 'messenger')
                                      ->get();
    
            $dataStaticUsage = DB::table('data_static')
                                ->select('data_static.value as value',
                                         'data_static.name as name',)
                                ->where('data_static.value', '=', 'Usage')
                                ->get();
            
            $param_location = array('dataStaticTelephone' => $data_static_telepon);
            $param_location['dataStaticMessenger'] = $data_static_messenger;
            $param_location['dataStaticUsage'] = $dataStaticUsage;
    
            return response()->json($param_location, 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => 'Failed',
                'token' =>  $e,
            ]);
        }

    }


    /**
     * @OA\Get(
     * path="/api/provinsilocation",
     * operationId="Get Provinsi Location",
     * tags={"Location"},
     * summary="Get Provinsi Location",
     * description="Get Provinsi Location",
     *   @OA\Response(
     *          response=201,
     *          description="Get Data Provinsi Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Get  Data Provinsi Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function getProvinsiLocation(Request $request)
    {

        try
        {

            $getProvinsi = DB::table('provinsi')
                            ->select('provinsi.kodeProvinsi as id',
                                     'provinsi.namaProvinsi as provinceName', )
                            ->get();

            return response()->json($getProvinsi, 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => 'Failed',
                'token' =>  $e,
            ]);
        }

    }



  /**
     * @OA\Get(
     * path="/api/kabupatenkotalocation/",
     * operationId="get kabupaten kota location",
     * tags={"Location"},
     * summary="Get Kabupaten Kota Location",
     * description="Get Kabupaten Kota Location",
     *     @OA\Parameter(
     *         in="path",
     *         name="provinceId",
     *         @OA\Schema(type="string")
     *     ),
     *   @OA\Response(
     *          response=201,
     *          description="Get Data Kabupaten Kota Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Data Kabupaten Kota Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function getKabupatenLocation(Request $request)
    {

        try
        {
            
            $request->validate(['provinceId' => 'required|max:10000']);
            $provinceId = $request->input('provinceId');
		
            $data_kabupaten = DB::table('kabupaten')
                                ->select('kabupaten.id as id',
                                        'kabupaten.kodeKabupaten as cityCode',
                                        'kabupaten.namaKabupaten as cityName')
                                ->where('kabupaten.kodeProvinsi', '=', $provinceId)
                                ->get();

            return response()->json($data_kabupaten, 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => 'Failed',
                'token' =>  $e,
            ]);
        
        }
    }


    /**
     * @OA\Post(
     * path="/api/datastatic",
     * operationId="Insert Data Static",
     * tags={"Location"},
     * summary="Insert Data Static",
     * description="Insert Data Static",
     *     @OA\RequestBody(
     *         @OA\JsonContent(* @OA\Examples(
     *        summary="Insert Data static",
     *        example = "Insert Data Static",
     *      value = {
     *          "keyword": "Pemakaian",
     *           "name": "Sharing Account",
     *          })),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"keyword","name"},
     *               @OA\Property(property="keyword", type="text"),
     *               @OA\Property(property="name", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function insertdatastatic(Request $request)
    {

        $request->validate([
            'keyword' => 'required|max:2555',
        ]);

        DB::beginTransaction();

        try
        {

            DB::table('data_static')->insert([
                'value' => $request->input('keyword'),
                'name' => $request->input('name'),
                'isDeleted' => 0,
            ]);

            DB::commit();

            return response()->json([
                'result' => 'success',
            ]);

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'result' => 'failed',
                'token' =>  $e,
            ]);

        }

    }

}
