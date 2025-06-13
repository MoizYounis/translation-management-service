<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Enum\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Contracts\TranslationContract;
use App\Exceptions\CustomException;
use App\Http\Requests\Api\TranslationRequest;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    public function __construct(private TranslationContract $translationContract) {}

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="Get all translations",
     *     operationId="translations",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         required=false,
     *         description="Locale to filter translations",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=false,
     *         description="Key to filter translations",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="value",
     *         in="query",
     *         required=false,
     *         description="Value to filter translations",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         required=false,
     *         description="Comma separated tags",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(
     *             type="integer",
     *             example=20
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function index()
    {
        try {
            $request = request();
            $perPage = $request->input('per_page', 10);
            $data = $request->only(['locale', 'key', 'value', 'tags']);
            $translations = $this->translationContract->index($perPage, $data);
            return $this->sendJson(true, __('lang.attributes.success'), $translations);
        } catch (\Throwable $th) {
            logMessage('translations', [], $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\POST(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="Store Translation",
     *     operationId="storeTranslation",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\RequestBody(
     *         description="Store Translation",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "value", "tags", "cdn_ready"},
     *
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="some_value"),
     *             @OA\Property(property="value", type="string", example="some value"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"mobile", "desktop"}
     *             ),
     *             @OA\Property(property="cdn_ready", type="boolean", example=true),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function store(TranslationRequest $request)
    {
        try {
            DB::beginTransaction();
            $translation = $this->translationContract->store($request->prepareRequest());
            if ($translation) {
                DB::commit();

                return $this->sendJson(true, __('lang.messages.created_successfully', ['attribute' => __('lang.attributes.translation')]), $translation->only(['id', 'locale', 'key', 'value', 'tags', 'cdn_ready']), 201);
            }
        } catch (CustomException $e) {
            DB::rollBack();
            return $this->sendJson(false, $e->getMessage());
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("store/translations", $request->prepareRequest(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\GET(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="translationDetail",
     *     operationId="translationDetail",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the  translation",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function show(string $id)
    {
        try {
            $translation_detail = $this->translationContract->show($id);
            return $this->sendJson(true, __("lang.attributes.success"), $translation_detail);
        } catch (CustomException $e) {
            return $this->sendJson(false, $e->getMessage());
        } catch (\Throwable $th) {
            logMessage("detail/translations/{$id}", $id, $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\PATCH(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Update  Translation",
     *     operationId="updateTranslation",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the  translation",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *      @OA\RequestBody(
     *         description="Update  Translation",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "value", "tags", "cdn_ready"},
     *
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="some_value"),
     *             @OA\Property(property="value", type="string", example="some value"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"mobile", "desktop"}
     *             ),
     *             @OA\Property(property="cdn_ready", type="boolean", example=true),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function update(TranslationRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $translation = $this->translationContract->update($id, $request->prepareRequest());
            if ($translation) {
                DB::commit();

                return $this->sendJson(true, __('lang.messages.updated_successfully', ['attribute' => __('lang.attributes.translation')]), $translation);
            }
        } catch (CustomException $e) {
            DB::rollBack();

            return $this->sendJson(false, $e->getMessage());
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("update/translation/{$id}", $request->prepareRequest(), $th->getMessage());

            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\DELETE(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Delete Translation",
     *     operationId="deleteTranslation",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the  translation",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $translation = $this->translationContract->destroy($id);
            if ($translation) {
                DB::commit();

                return $this->sendJson(true, __('lang.messages.deleted_successfully', ['attribute' => __('lang.attributes.translation')]));
            }
        } catch (CustomException $e) {
            DB::rollBack();

            return $this->sendJson(false, $e->getMessage());
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("delete/translations/{$id}", [], $th->getMessage());

            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/translations-export",
     *     tags={"Translations"},
     *     summary="Export all translations",
     *     operationId="translationsExport",
     *     security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function export()
    {
        try {
            $translations = $this->translationContract->export();
            return $this->sendJson(true, __('lang.attributes.success'), $translations);
        } catch (\Throwable $th) {
            logMessage('translations-export', [], $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }
}
