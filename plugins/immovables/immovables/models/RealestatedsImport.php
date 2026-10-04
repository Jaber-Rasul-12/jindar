<?php namespace Immovables\Immovables\Models;

use Backend\Models\ImportModel;
use Exception;
use Carbon\Carbon;

class RealestatedsImport extends ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'first_team' => 'required|string|max:255',
        'second_team' => 'required|string|max:255',
        'country' => 'nullable|string|exists:immovables_immovables_countries,name',
        'type' => 'nullable|string|max:100',
        'detail' => 'nullable|string',
        'area' => 'nullable|numeric|min:0',
        'syria_price' => 'nullable|numeric|min:0',
        'dollar_price' => 'nullable|numeric|min:0',
        'purchase_date' => 'nullable|date',
        'point' => 'nullable|string',
    ];

    /**
     * @var array Custom validation messages
     */
    public $customMessages = [
        'country.exists' => 'الدولة ":value" غير موجودة في قاعدة البيانات. يرجى التأكد من الاسم.',
        'country.required' => 'حقل الدولة مطلوب',
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {
            try {


            $data['dollar_price'] = str_replace(['$', ' ', ',' ,'_'], '', $data['dollar_price']);



                // إنشاء سجل جديد
                $realestated = new Realestated;
                
                // تعيين الحقول مع استثناء حقل 'country' لأنه ليس في الجدول
                $realestated->first_team = $data['first_team'] ?? null;
                $realestated->second_team = $data['second_team'] ?? null;
                $realestated->type = $data['type'] ?? null;
                $realestated->country_id = 1; // إضافة الـ ID من جدول الدول
                $realestated->detail = $data['detail'] ?? null;
                $realestated->area = $data['area'] ?? null;
                $realestated->syria_price = $data['syria_price'] ?? null;
                $realestated->dollar_price = $data['dollar_price'] && !empty($data['dollar_price']) ? null : $data['dollar_price'];
                $realestated->purchase_date =   $data['purchase_date'] ? $realestated->purchase_date = Carbon::createFromFormat('m/d/y', $data['purchase_date'])->format('Y-m-d') : null;
                $realestated->point = $data['point'] ?? null;
                
                $realestated->save();

                $this->logCreated();
                
            } catch (Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }
}