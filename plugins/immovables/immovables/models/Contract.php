<?php namespace Immovables\Immovables\Models;

use Model;
// use Winter\Storm\Database\Builder;
// use BackendAuth;
/**
 * Model
 */
use Carbon\Carbon;

use Jacob\Logbook\Traits\LogChanges;
class Contract extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    
   
    use LogChanges;

  public $logBookModelName = 'immovables.immovables::lang.plugin.contracts';
  public static function changeLogBookDisplayColumn($column)
  {
    return 'immovables.immovables::lang.model.contract.' . $column;
  }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'immovables_immovables_contracts';

      public $rules = [
        // 'customer_owner_id' => 'required|integer|exists:immovables_immovables_customers,id',
        // 'customer_tenant_id' => 'required|integer|exists:immovables_immovables_customers,id',
        // 'realestated_id' => 'required|integer|exists:immovables_immovables_realestateds,id',
        'lease_purpose' => 'required|string|max:255',
        'start_date' => 'required|date|before:end_date',
        'end_date' => 'required|date|after:start_date',
        'total_duration' => 'required|string|max:255',
        'rental_amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|max:255',
        'payment_day' => 'required|date',
        'security_deposit' => 'required|numeric|min:0',
        'default_days' => 'required|numeric|min:0',
        'termination_notice_days' => 'required|numeric|min:0',
        'renewal_notice_days' => 'required|numeric|min:0',
        'judicial_district' => 'required|string|max:255',
        'witness_one' => 'required|string|max:255',
        'witness_tow' => 'required|string|max:255',
        'status' => 'required|string|max:255',
    ];


            public $belongsTo = [
        'realestated' => ['Immovables\Immovables\Models\Realestated', 'key' => 'realestated_id'],
        'customer_owner' => ['Immovables\Immovables\Models\Customer', 'key' => 'customer_owner_id'],
        'customer_tenant' => ['Immovables\Immovables\Models\Customer', 'key' => 'customer_tenant_id'],
    ];

        public $attachMany = [
        'photos' => 'System\Models\File'
    ];


        /**
     * Generates the HTML for contract preview and printing.
     *
     * @return string
     */
    public function getContractHtml()
    {
        // جلب البيانات المرتبطة
        $owner = $this->customer_owner;
        $tenant = $this->customer_tenant;
        $property = $this->realestated;

        // دالة مساعدة لتنسيق التواريخ
        $formatDate = function($dateValue, $format = 'Y-m-d') {
            if (empty($dateValue)) {
                return '...........................';
            }
            try {
                if ($dateValue instanceof \DateTime) {
                    return $dateValue->format($format);
                }
                return Carbon::parse($dateValue)->format($format);
            } catch (\Exception $e) {
                return '...........................';
            }
        };

        // تعبئة بيانات المؤجر
        $ownerName = $owner ? $owner->full_name : '...........................';
        $ownerId = $owner ? $owner->id_number : '...........................';
        $ownerAddress = $owner ? $owner->address : '...........................';
        $ownerPhone = $owner ? $owner->phone : '...........................';

        // تعبئة بيانات المستأجر
        $tenantName = $tenant ? $tenant->full_name : '...........................';
        $tenantId = $tenant ? $tenant->id_number : '...........................';
        $tenantAddress = $tenant ? $tenant->address : '...........................';
        $tenantPhone = $tenant ? $tenant->phone : '...........................';

        // تعبئة بيانات العقار (من نموذج Realestated)
        $propertyType = $property ? $property->type : '...........................';
        $propertyDetail = $property ? $property->detail : '...........................';
        $propertyArea = $property ? $property->area : '...........................';
        $propertyCountry = ($property && $property->country) ? $property->country->name : '...........................';
        // يمكن إضافة حقول إضافية من العقار مثل العنوان الكامل إذا كانت موجودة
        // هنا نأخذ التفاصيل من حقل detail والذي قد يحتوي على العنوان
        $propertyFullAddress = $property ? $property->detail : '...........................';

        // تواريخ العقد والمبالغ
        $startDate = $formatDate($this->start_date);
        $endDate = $formatDate($this->end_date);
        $totalDuration = $this->total_duration ?? '...........................';
        $rentalAmount = $this->rental_amount ?? 0;
        $paymentMethod = $this->payment_method ?? '...........................';
        $paymentDay = $this->payment_day ? $formatDate($this->payment_day, 'd') : '...........................'; // نأخذ اليوم فقط
        $securityDeposit = $this->security_deposit ?? 0;
        $defaultDays = $this->default_days ?? 0;
        $terminationNoticeDays = $this->termination_notice_days ?? 0;
        $renewalNoticeDays = $this->renewal_notice_days ?? 0;
        $judicialDistrict = $this->judicial_district ?? '...........................';
        $witness1 = $this->witness_one ?? '...........................';
        $witness2 = $this->witness_tow ?? '...........................';
        $status = $this->status ?? '...........................';
        $today = now()->format('Y-m-d');

        // صورة الخلفية (يمكن تغييرها أو تعطيلها)
        $imageUrl = e(\Backend\Models\BrandSetting::getFavicon());

        // بناء HTML للعقد
        $html = <<<HTML
        <div class="contract-wrapper" style="direction: rtl; font-family: 'Tahoma', 'Arial', sans-serif; max-width: 1100px; margin: 0 auto;  padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
            
            <!-- صورة خلفية خفيفة -->


            <div style="background: #fff; padding: 40px 50px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); position: relative; z-index: 1;">

                        <img src="{$imageUrl}" 
                 class="contract-background-image"
                 style="position: fixed; 
                        top: 0; left: 0; 
                        width: 100%; height: 100%; 
                        object-fit: cover; 
                        opacity: 0.05; 
                        z-index: 0; 
                        pointer-events: none;"
                 alt="خلفية العقد">
                <!-- رأس العقد -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px double #2c3e50; padding-bottom: 15px; margin-bottom: 25px;">
                    <div style="font-size: 22px; font-weight: bold; color: #2c3e50;">عقد إيجار عقار</div>
                    <div style="text-align: left; font-size: 14px; color: #7f8c8d;">رقم العقد: {$this->id}</div>
                </div>

                <!-- المقدمة -->
                <div style="margin: 25px 0; padding: 15px; background: #fdfaf0; border-radius: 8px; border: 1px solid #f1c40f;">
                    <h3 style="color: #8e44ad; margin-top: 0;">مقدمة</h3>
                    <p style="line-height: 1.9; text-align: justify; margin: 0;">
                        إنه في يوم {$today}، تم الاتفاق بين كل من:<br>
                        <strong>الفريق الأول (المؤجر):</strong> {$ownerName}، رقم الهوية: {$ownerId}، العنوان: {$ownerAddress}، هاتف: {$ownerPhone}.<br>
                        <strong>الفريق الثاني (المستأجر):</strong> {$tenantName}، رقم الهوية: {$tenantId}، العنوان: {$tenantAddress}، هاتف: {$tenantPhone}.<br>
                        بعد أن كان الفريق الأول هو المالك الشرعي للعقار الموصوف أدناه، ورغبة منه في تأجيره، ورغبة من الفريق الثاني في استئجاره للغرض المحدد، اتفقا وهما بكامل الأهلية القانونية على ما يلي:
                    </p>
                </div>

                <!-- المادة 1: وصف العقار -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 1: وصف العقار المؤجر</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 30px; background: #fcfcfc; padding: 15px; border-radius: 6px;">
                        <span><strong>نوع العقار:</strong> {$propertyType}</span>
                        <span><strong>المساحة:</strong> {$propertyArea} م²</span>
                        <span><strong>الموقع:</strong> {$propertyCountry}</span>
                        <span><strong>التفاصيل:</strong> {$propertyDetail}</span>
                        <span><strong>العنوان الكامل:</strong> {$propertyFullAddress}</span>
                        <span><strong>المرافق المتوفرة:</strong> (ماء، كهرباء، هاتف، تدفئة...) </span>
                    </div>
                </div>

                <!-- المادة 2: غرض الإيجار -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 2: غرض الإيجار</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px;">
                        <p><strong>الغرض:</strong> {$this->lease_purpose}</p>
                    </div>
                </div>

                <!-- المادة 3: مدة الإيجار -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 3: مدة الإيجار</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px;">
                        <p><strong>بداية العقد:</strong> {$startDate}</p>
                        <p><strong>نهاية العقد:</strong> {$endDate}</p>
                        <p><strong>المدة الإجمالية:</strong> {$totalDuration}</p>
                    </div>
                </div>

                <!-- المادة 4: بدل الإيجار -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 4: بدل الإيجار وطريقة الدفع</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px;">
                        <p>يُحدد بدل الإيجار بمبلغ <strong style="color: #c0392b;">{$rentalAmount} ليرة سورية</strong> شهرياً (أو سنوياً حسب الاتفاق).</p>
                        <p>طريقة الدفع: {$paymentMethod}، في اليوم {$paymentDay} من كل شهر مقدماً.</p>
                        <p>يتحمل المستأجر كافة الرسوم والضرائب المترتبة على الإيجار.</p>
                    </div>
                </div>

                <!-- المادة 5: التأمين النقدي -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 5: التأمين النقدي (الضمان)</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px;">
                        <p>دفع المستأجر للمؤجر مبلغ <strong style="color: #c0392b;">{$securityDeposit} ليرة سورية</strong> كتأمين نقدي ضماناً لتنفيذ التزاماته.</p>
                        <p>يُعاد هذا المبلغ بعد انتهاء العقد وتسليم العقار، بعد خصم أي مستحقات أو تعويضات عن أضرار.</p>
                    </div>
                </div>

                <!-- المادة 6: المرافق والفواتير -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 6: المرافق والفواتير</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px; line-height: 1.8;">
                        <p><strong>يتحمل المستأجر تكاليف:</strong> الكهرباء، المياه، الهاتف، الإنترنت، رسوم التدفئة (إن وجدت).</p>
                        <p><strong>يتحمل المؤجر:</strong> الرسوم البلدية، رسوم الصيانة الكبرى للبناء.</p>
                    </div>
                </div>

                <!-- المادة 7: التزامات المؤجر -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 7: التزامات المؤجر</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px; line-height: 1.8;">
                        <ul style="padding-right: 20px; margin: 0;">
                            <li>تسليم العقار خالياً من الشواغل وبحالة صالحة للاستعمال.</li>
                            <li>إجراء الصيانة الجوهرية للعقار (السباكة الرئيسية، الكهرباء العامة، الهيكل).</li>
                            <li>عدم التدخل في حق المستأجر بالانتفاع بالعقار.</li>
                            <li>ضمان العيوب الخفية التي قد تظهر.</li>
                        </ul>
                    </div>
                </div>

                <!-- المادة 8: التزامات المستأجر -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 8: التزامات المستأجر</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px; line-height: 1.8;">
                        <ul style="padding-right: 20px; margin: 0;">
                            <li>سداد بدل الإيجار في مواعيده المحددة.</li>
                            <li>استعمال العقار للغرض المتفق عليه فقط، وعدم استخدامه لأغراض غير مشروعة.</li>
                            <li>إجراء الصيانة الاعتيادية البسيطة (استبدال المصابيح، حنفيات الماء).</li>
                            <li>عدم إجراء تعديلات إنشائية دون موافقة خطية من المؤجر.</li>
                            <li>عدم تأجير العقار من الباطن دون موافقة المؤجر.</li>
                            <li>المحافظة على العقار وإعادته عند انتهاء العقد بحالته الأصلية (مع الاستهلاك الطبيعي).</li>
                        </ul>
                    </div>
                </div>

                <!-- المادة 9: الفسخ والإخلاء -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 9: الفسخ والإخلاء</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px; line-height: 1.8;">
                        <ul style="padding-right: 20px; margin: 0;">
                            <li>ينتهي العقد بانتهاء مدته المحددة، ويلزم المستأجر بإخلاء العقار فوراً.</li>
                            <li>للمؤجر فسخ العقد وإخلاء المستأجر في حال تأخر عن سداد الإيجار لمدة <strong>{$defaultDays}</strong> يوماً دون عذر، أو الإخلال بأي التزام جوهري.</li>
                            <li>يمكن لأي من الطرفين فسخ العقد قبل موعده بشرط الإخطار قبل <strong>{$terminationNoticeDays}</strong> يوماً.</li>
                        </ul>
                    </div>
                </div>

                <!-- المادة 10: التجديد -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 10: التجديد</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px;">
                        <p>لا يُجدد العقد تلقائياً. يجب على الطرفين الاتفاق على التجديد خطياً قبل <strong>{$renewalNoticeDays}</strong> يوماً من انتهاء العقد.</p>
                    </div>
                </div>

                <!-- المادة 11: أحكام عامة -->
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">المادة 11: أحكام عامة</h3>
                    <div style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px; line-height: 1.8;">
                        <ul style="padding-right: 20px; margin: 0;">
                            <li>تسري على هذا العقد أحكام قانون الإيجارات السوري رقم 20 لعام 2015 والقانون المدني.</li>
                            <li>أي تعديل أو إضافة على العقد تكون باطلة ما لم تكن خطية وموقعة من الطرفين.</li>
                            <li>الاختصاص القضائي للمحاكم في {$judicialDistrict} (مكان وقوع العقار).</li>
                            <li>حرر العقد من نسختين أصليتين، بيد كل طرف نسخة.</li>
                        </ul>
                    </div>
                </div>

                <!-- المادة 12: الشهود والتوقيعات -->
                <div style="border-top: 2px solid #2c3e50; padding-top: 25px; margin-top: 10px;">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                        <div style="width: 45%;">
                            <h4 style="margin: 0 0 10px 0; color: #2980b9;">الفريق الأول (المؤجر)</h4>
                            <p><strong>الاسم:</strong> {$ownerName}</p>
                            <p><strong>التوقيع:</strong> ........................</p>
                            <p><strong>الختم:</strong> ........................</p>
                        </div>
                        <div style="width: 45%;">
                            <h4 style="margin: 0 0 10px 0; color: #27ae60;">الفريق الثاني (المستأجر)</h4>
                            <p><strong>الاسم:</strong> {$tenantName}</p>
                            <p><strong>التوقيع:</strong> ........................</p>
                            <p><strong>الختم:</strong> ........................</p>
                        </div>
                    </div>
                    <div style="margin-top: 20px; background: #ecf0f1; padding: 10px 20px; border-radius: 6px;">
                        <p><strong>شاهد أول:</strong> {$witness1}</p>
                        <p><strong>شاهد ثان:</strong> {$witness2}</p>
                    </div>
                    <p style="text-align: left; margin-top: 20px; color: #7f8c8d;"><strong>حرر في:</strong> {$judicialDistrict} <strong>بتاريخ:</strong> {$today}</p>
                </div>

            </div> <!-- نهاية الـ inner div -->
        </div> <!-- نهاية الـ wrapper -->
HTML;

        return $html;
    }


}
