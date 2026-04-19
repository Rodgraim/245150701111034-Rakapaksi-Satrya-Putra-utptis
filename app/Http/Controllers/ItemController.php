<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class ItemController extends Controller
{
    private $filePath = 'items.json';

    private function getItems()
    {
        if (!Storage::exists($this->filePath)) {
            Storage::put($this->filePath, json_encode([]));
        }
        $content = Storage::get($this->filePath);
        return json_decode($content, true) ?? [];
    }

    private function saveItems(array $items)
    {
        Storage::put($this->filePath, json_encode($items, JSON_PRETTY_PRINT));
    }

    #[OA\Get(
        path: '/api/items',
        summary: 'Menampilkan semua item',
        tags: ['Items'],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil menampilkan semua item')
        ]
    )]
    public function index()
    {
        $items = $this->getItems();
        return response()->json($items);
    }

    #[OA\Post(
        path: '/api/items',
        summary: 'Membuat item baru',
        tags: ['Items'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price'],
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'price', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Item berhasil dibuat')
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $items = $this->getItems();
        
        $newId = 1;
        if (count($items) > 0) {
            $ids = array_column($items, 'id');
            $newId = max($ids) + 1;
        }

        $newItem = [
            'id' => $newId,
            'name' => $request->name,
            'price' => $request->price
        ];

        $items[] = $newItem;
        $this->saveItems($items);

        return response()->json($newItem, 201);
    }

    #[OA\Get(
        path: '/api/items/{id}',
        summary: 'Menampilkan item berdasarkan ID',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Item ditemukan'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan')
        ]
    )]
    public function show($id)
    {
        $items = $this->getItems();
        $key = array_search($id, array_column($items, 'id'));

        if ($key === false) {
            return response()->json(['message' => "Item dengan ID {$id} tidak Ditemukan"], 404);
        }

        return response()->json($items[$key]);
    }

    #[OA\Put(
        path: '/api/items/{id}',
        summary: 'Mengedit seluruh data barang',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price'],
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'price', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Item berhasil diupdate'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan')
        ]
    )]
    public function update(Request $request, $id)
    {
        $items = $this->getItems();
        $key = array_search($id, array_column($items, 'id'));

        if ($key === false) {
            return response()->json(['message' => "Item dengan ID {$id} tidak Ditemukan"], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $items[$key]['name'] = $request->name;
        $items[$key]['price'] = $request->price;
        $this->saveItems($items);

        return response()->json($items[$key]);
    }

    #[OA\Patch(
        path: '/api/items/{id}',
        summary: 'Mengedit salah satu data dari barang',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'price', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Item berhasil diupdate (sebagian)'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan')
        ]
    )]
    public function patch(Request $request, $id)
    {
        $items = $this->getItems();
        $key = array_search($id, array_column($items, 'id'));

        if ($key === false) {
            return response()->json(['message' => "Item dengan ID {$id} tidak Ditemukan"], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('name')) {
            $items[$key]['name'] = $request->name;
        }

        if ($request->has('price')) {
            $items[$key]['price'] = $request->price;
        }

        $this->saveItems($items);

        return response()->json($items[$key]);
    }

    #[OA\Delete(
        path: '/api/items/{id}',
        summary: 'Menghapus barang',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Item berhasil dihapus'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan')
        ]
    )]
    public function destroy($id)
    {
        $items = $this->getItems();
        $key = array_search($id, array_column($items, 'id'));

        if ($key === false) {
            return response()->json(['message' => "Item dengan ID {$id} tidak Ditemukan"], 404);
        }

        array_splice($items, $key, 1);
        $this->saveItems($items);

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
