<?php namespace Immovables\Immovables\Models;

use Model;
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

    public $table = 'immovables_immovables_contracts';

    public $rules = [
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
        'customer_owner' => ['Immovables\Immovables\Models\Customer', 'key' => 'customer_owner_id'],
        'customer_tenant' => ['Immovables\Immovables\Models\Customer', 'key' => 'customer_tenant_id'],
    ];

    public $attachMany = [
        'photos' => 'System\Models\File'
    ];

    public function getContractHtml()
    {
        $owner = $this->customer_owner;
        $tenant = $this->customer_tenant;
        $dots = '...........................';

        $formatDate = function($dateValue, $format = 'Y-m-d') use ($dots) {
            if (empty($dateValue)) return $dots;
            try {
                if ($dateValue instanceof \DateTime) return $dateValue->format($format);
                return Carbon::parse($dateValue)->format($format);
            } catch (\Exception $e) {
                return $dots;
            }
        };

        // بيانات المؤجر
        $ownerName       = $owner ? $owner->full_name : $dots;
        $ownerFather     = $owner ? $owner->father_name : $dots;
        $ownerMother     = $owner ? $owner->mother_name : $dots;
        $ownerId         = $owner ? $owner->id_number : $dots;
        $ownerBirthPlace = $owner ? $owner->birth_place : $dots;
        $ownerBirthDate  = $owner ? $formatDate($owner->birth_date) : $dots;
        $ownerAddress    = $owner ? $owner->address : $dots;
        $ownerPhone      = $owner ? $owner->phone : $dots;

        // بيانات المستأجر
        $tenantName       = $tenant ? $tenant->full_name : $dots;
        $tenantFather     = $tenant ? $tenant->father_name : $dots;
        $tenantMother     = $tenant ? $tenant->mother_name : $dots;
        $tenantId         = $tenant ? $tenant->id_number : $dots;
        $tenantBirthPlace = $tenant ? $tenant->birth_place : $dots;
        $tenantBirthDate  = $tenant ? $formatDate($tenant->birth_date) : $dots;
        $tenantAddress    = $tenant ? $tenant->address : $dots;
        $tenantPhone      = $tenant ? $tenant->phone : $dots;

        // العقار
        $pGovernorate = $this->property_governorate ?? $dots;
        $pCity        = $this->property_city ?? $dots;
        $pDistrict    = $this->property_district ?? $dots;
        $pStreet      = $this->property_street ?? $dots;
        $pBuildingNo  = $this->property_building_no ?? $dots;
        $pFloor       = $this->property_floor ?? $dots;
        $pParcelNo    = $this->property_parcel_no ?? $dots;
        $pDescription = $this->property_description ?? $dots;
        $pFurnished   = $this->property_is_furnished ? 'نعم' : 'لا';
        $pFurniture   = $this->property_furniture_list ?? $dots;

        // العقد
        $contractDayName = $this->contract_day_name ?? $dots;
        $contractDate    = $this->contract_date ? $formatDate($this->contract_date) : $dots;
        $startDate       = $formatDate($this->start_date);
        $endDate         = $formatDate($this->end_date);
        $totalDuration   = $this->total_duration ?? $dots;
        $rentalAmount    = $this->rental_amount ?? 0;
        $rentPeriod      = $this->rent_period ?? $dots;
        $paymentMethod   = $this->payment_method ?? $dots;
        $paymentDay      = $this->payment_day ? $formatDate($this->payment_day) : $dots;
        $securityDeposit = $this->security_deposit ?? 0;
        $defaultDays     = $this->default_days ?? 0;
        $terminationDays = $this->termination_notice_days ?? 0;
        $renewalDays     = $this->renewal_notice_days ?? 0;
        $judicialDistrict= $this->judicial_district ?? $dots;
        $witness1        = $this->witness_one ?? $dots;
        $witness2        = $this->witness_tow ?? $dots;
        $status          = $this->status ?? $dots;

        $utilitiesTenant = $this->utilities_on_tenant ?? $dots;
        $utilitiesLessor = $this->utilities_on_lessor ?? $dots;
        $copiesCount     = $this->copies_count ?? $dots;
        $copyHolder      = $this->copy_holder ?? $dots;

        $leasePurpose    = $this->lease_purpose ?? $dots;

        // محضر الاستلام
        $handoverDate    = $this->handover_date ? $formatDate($this->handover_date) : $dots;
        $keysCount       = $this->keys_count ?? $dots;
        $electricNo      = $this->electric_meter_no ?? $dots;
        $electricReading = $this->electric_reading ?? $dots;
        $waterNo         = $this->water_meter_no ?? $dots;
        $waterReading    = $this->water_reading ?? $dots;
        $gasMeter        = $this->gas_meter ?? $dots;
        $doorsState      = $this->doors_windows_state ?? $dots;
        $bathroomsState  = $this->bathrooms_state ?? $dots;
        $kitchenState    = $this->kitchen_state ?? $dots;
        $heatingState    = $this->heating_ac_state ?? $dots;
        $otherNotes      = $this->other_notes ?? $dots;

        $imageUrl = \Backend\Models\BrandSetting::getFavicon();

        $html = <<<HTML
        <!-- صورة الخلفية (تظهر فقط عند الطباعة) -->
        <div class="contract-bg-print" style="display: none;">
            <img src="{$imageUrl}" alt="خلفية">
        </div>

        <div class="contract-wrapper" style="direction: rtl; background:#fff; padding: 15px 20px; font-size: 14px; line-height: 1.7; color:#1a1a1a;">

            <!-- ترويسة -->
            <div style="text-align:center; border-bottom: 3px double #2c3e50; padding-bottom: 10px; margin-bottom: 18px;">
                <h1 style="margin:0; font-size: 22px; font-weight: 900; color:#2c3e50;">عقد تأجير عقارات شركة Jir للتجارة العامة</h1>
                <div style="margin-top:5px; font-size:12px; color:#7f8c8d;">رقم العقد: {$this->id}</div>
            </div>

            <!-- التاريخ -->
            <p style="font-size:14px; margin:14px 0;">
                إنه في يوم <span style="border-bottom:1px dotted #555; padding:0 15px;">{$contractDayName}</span>
                الموافق <span style="border-bottom:1px dotted #555; padding:0 12px;">{$contractDate}</span>،
                تم الاتفاق والتراضي بين كل من:
            </p>

            <!-- المؤجر -->
            <div style="margin: 15px 0; padding: 10px 15px; background:#f8f9fb; border-right: 4px solid #2980b9; border-radius:4px;">
                <h3 style="margin:0 0 8px 0; color:#2980b9; font-size:15px;">أولاً: المؤجر</h3>
                <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                    <tr><td style="padding:4px 0; width:35%;"><strong>السيد/السيدة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerName}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>اسم الأب:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerFather}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>اسم الأم:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerMother}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>رقم الهوية:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerId}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>مكان الولادة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerBirthPlace}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>تاريخ الولادة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerBirthDate}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>العنوان:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$ownerAddress}</td></tr>
                </table>
                <p style="margin:8px 0 0 0; font-size:13px;">ويشار إليه بـ <strong>"المؤجر"</strong>.</p>
            </div>

            <!-- المستأجر -->
            <div style="margin: 15px 0; padding: 10px 15px; background:#f8f9fb; border-right: 4px solid #27ae60; border-radius:4px;">
                <h3 style="margin:0 0 8px 0; color:#27ae60; font-size:15px;">ثانياً: المستأجر</h3>
                <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                    <tr><td style="padding:4px 0; width:35%;"><strong>السيد/السيدة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantName}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>اسم الأب:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantFather}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>اسم الأم:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantMother}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>رقم الهوية:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantId}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>مكان الولادة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantBirthPlace}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>تاريخ الولادة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantBirthDate}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>العنوان:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$tenantAddress}</td></tr>
                </table>
                <p style="margin:8px 0 0 0; font-size:13px;">ويشار إليه بـ <strong>"المستأجر"</strong>.</p>
            </div>

            <p style="font-size:14px; margin:15px 0; text-align:justify;">وقد أقر الطرفان بأهليتهما القانونية للتعاقد، واتفقا على ما يلي:</p>

            <!-- المادة 1 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 1 – المأجور</h3>
                <p style="font-size:13.5px; text-align:justify; margin:6px 0;">أجر المؤجر إلى المستأجر المنزل الواقع في:</p>
                <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                    <tr><td style="padding:4px 0; width:30%;"><strong>المحافظة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pGovernorate}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>المدينة/البلدة:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pCity}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>الحي:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pDistrict}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>الشارع:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pStreet}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>رقم البناء:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pBuildingNo}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>الطابق:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pFloor}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>رقم العقار/المقسم:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pParcelNo}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>وهو مؤلف من:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pDescription}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>مفروش:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pFurnished}</td></tr>
                    <tr><td style="padding:4px 0;"><strong>قائمة الأثاث:</strong></td><td style="padding:4px 0; border-bottom:1px dotted #aaa;">{$pFurniture}</td></tr>
                </table>
                <p style="font-size:13.5px; text-align:justify; margin:8px 0 0 0;">
                    ويشمل الإيجار، إن وجد: المطبخ، الحمامات، الشرفة، المستودع، المرآب، والمرافق والتجهيزات المبينة في محضر الاستلام المرفق.
                </p>
            </div>

            <!-- المادة 2 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 2 – الغرض من الإيجار</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    خصص المأجور لغرض: <strong>{$leasePurpose}</strong>، ولا يجوز استعماله لنشاط تجاري أو مهني أو لأي غرض مخالف للقوانين أو لطبيعة العقار إلا بموافقة خطية من المؤجر والجهات المختصة عند وجوبها.
                </p>
            </div>

            <!-- المادة 3 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 3 – مدة الإيجار</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    مدة هذا العقد هي <strong>{$totalDuration}</strong> تبدأ من تاريخ <strong>{$startDate}</strong> وتنتهي بتاريخ <strong>{$endDate}</strong>.
                    ويجوز للطرفين الاتفاق خطياً على تجديد العقد قبل انتهاء مدته بـ <strong>{$renewalDays}</strong> يوماً.
                </p>
            </div>

            <!-- المادة 4 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 4 – بدل الإيجار</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0 0 6px 0;">اتفق الطرفان على أن بدل الإيجار هو مبلغ وقدره:</p>
                <p style="font-size:14px; margin:6px 0;">( <strong>{$rentalAmount}</strong> ) ليرة سورية <strong>{$rentPeriod}</strong>.</p>
                <p style="font-size:13.5px; text-align:justify; margin:6px 0 0 0;">
                    ويُدفع البدل في موعد أقصاه <strong>{$paymentDay}</strong> من كل شهر/سنة، بموجب <strong>{$paymentMethod}</strong>.
                </p>
            </div>

            <!-- المادة 5 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 5 – التأمين</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0 0 6px 0;">دفع المستأجر للمؤجر عند توقيع العقد مبلغاً قدره:</p>
                <p style="font-size:14px; margin:6px 0;"><strong>{$securityDeposit}</strong> ليرة سورية</p>
                <p style="font-size:13.5px; text-align:justify; margin:6px 0 0 0;">
                    كتأمين ضمان، يرد إليه عند انتهاء الإيجار وتسليم المأجور.
                </p>
            </div>

            <!-- المادة 6 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 6 – تسليم المأجور</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    يقر المستأجر بأنه عاين المأجور معاينة تامة، ووجده صالحاً للسكن ومطابقاً للحالة المبينة في محضر الاستلام المرفق.
                </p>
            </div>

            <!-- المادة 7 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 7 – التزامات المؤجر</h3>
                <ol style="font-size:13.5px; padding-right:22px; margin:0;">
                    <li style="margin-bottom:4px;">تسليم المأجور بالحالة المتفق عليها.</li>
                    <li>إجراء الإصلاحات الأساسية التي لا تكون ناشئة عن سوء استعمال المستأجر.</li>
                </ol>
            </div>

            <!-- المادة 8 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 8 – التزامات المستأجر</h3>
                <ol style="font-size:13.5px; padding-right:22px; margin:0;">
                    <li style="margin-bottom:4px;">دفع بدل الإيجار في مواعيده.</li>
                    <li style="margin-bottom:4px;">المحافظة على المأجور واستعماله استعمالاً مألوفاً ومشروعاً.</li>
                    <li style="margin-bottom:4px;">عدم إجراء تغييرات جوهرية دون موافقة المؤجر الخطية.</li>
                    <li style="margin-bottom:4px;">عدم تأجير المأجور من الباطن إلا وفقاً للقانون.</li>
                    <li style="margin-bottom:4px;">تحمل تكاليف الأضرار الناتجة عن سوء استعماله أو إهماله.</li>
                    <li>إعادة المأجور عند انتهاء العقد بحالته الأصلية مع مراعاة الاستهلاك الطبيعي.</li>
                </ol>
            </div>

            <!-- المادة 9 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 9 – الماء والكهرباء والخدمات</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0 0 6px 0;">تكون رسوم استهلاك الكهرباء والماء والغاز والاتصالات على عاتق:</p>
                <p style="font-size:14px; border-bottom:1px dotted #aaa; padding:4px 0; margin:0 0 8px 0;"><strong>{$utilitiesTenant}</strong></p>
                <p style="font-size:13.5px; text-align:justify; margin:0 0 6px 0;">أما الالتزامات والرسوم الأخرى المتعلقة بملكية العقار فتكون على عاتق:</p>
                <p style="font-size:14px; border-bottom:1px dotted #aaa; padding:4px 0; margin:0;"><strong>{$utilitiesLessor}</strong></p>
            </div>

            <!-- المادة 10 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 10 – الصيانة والإصلاح</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    يتحمل المستأجر أعمال الصيانة البسيطة، بينما يتحمل المؤجر الإصلاحات الأساسية التي لا تكون بسبب خطأ أو سوء استعمال من المستأجر.
                </p>
            </div>

            <!-- المادة 11 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 11 – انتهاء العقد والإخلاء</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    ينتهي عقد الإيجار بانتهاء مدته. وعند انتهاء العلاقة الإيجارية، يلتزم المستأجر بتسليم المأجور ومفاتيحه وملحقاته.
                    في حال رغبة أحد الطرفين بإنهاء العقد قبل موعده، يجب الإخطار قبل <strong>{$terminationDays}</strong> يوماً.
                </p>
            </div>

            <!-- المادة 12 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 12 – المخالفات والفسخ</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    في حال إخلال أحد الطرفين بالتزام جوهري، يحق للطرف المتضرر المطالبة بحقوقه وفقاً للقانون.
                    وفي حال تأخر المستأجر عن سداد الإيجار لمدة <strong>{$defaultDays}</strong> يوماً، يحق للمؤجر فسخ العقد.
                </p>
            </div>

            <!-- المادة 13 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 13 – عنوان التبليغ</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    يعتبر العنوان المبين في صدر هذا العقد عنواناً مختاراً لكل طرف لأغراض المراسلات والتبليغات المتعلقة بالعقد.
                </p>
            </div>

            <!-- المادة 14 -->
            <div class="contract-article" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 14 – حل النزاعات</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    في حال نشوء أي نزاع، يسعى الطرفان إلى حله ودياً، وفي حال تعذر ذلك تكون المحاكم السورية المختصة هي المرجع.
                    الدائرة القضائية المختصة: <strong>{$judicialDistrict}</strong>.
                </p>
            </div>

            <!-- المادة 15 -->
            <div class="contract-article page-break-before" style="margin:14px 0;">
                <h3 style="background:#2c3e50; color:#fff; padding:6px 12px; border-radius:4px; font-size:14px; margin:0 0 8px 0;">المادة 15 – النسخ</h3>
                <p style="font-size:13.5px; text-align:justify; margin:0;">
                    حرر هذا العقد من <strong>{$copiesCount}</strong> نسخ أصلية، تسلم كل طرف نسخة منها، واحتفظت نسخة لدى <strong>{$copyHolder}</strong> إن وجدت.
                </p>
                <p style="font-size:13.5px; text-align:justify; margin:8px 0 0 0;">
                    ويقر الطرفان بأنهما قرآ العقد وفهما جميع بنوده ووافقا عليها بإرادتهما الحرة.
                </p>
            </div>

            <!-- التوقيعات -->
            <div class="signatures-block" style="display:flex; justify-content:space-between; margin-top:30px; gap:15px;">
                <div style="flex:1; font-size:13.5px;">
                    <p style="font-weight:bold; margin:0 0 18px 0;">المؤجر:</p>
                    <p style="margin:0 0 10px 0;">الاسم: <strong>{$ownerName}</strong></p>
                    <p style="margin:0;">التوقيع: _______________________</p>
                </div>
                <div style="flex:1; font-size:13.5px;">
                    <p style="font-weight:bold; margin:0 0 18px 0;">الشاهد الأول:</p>
                    <p style="margin:0 0 10px 0;">الاسم: <strong>{$witness1}</strong></p>
                    <p style="margin:0;">رقم الهوية والتوقيع: _____________</p>
                </div>
            </div>

            <div class="signatures-block" style="display:flex; justify-content:space-between; margin-top:25px; gap:15px;">
                <div style="flex:1; font-size:13.5px;">
                    <p style="font-weight:bold; margin:0 0 18px 0;">المستأجر:</p>
                    <p style="margin:0 0 10px 0;">الاسم: <strong>{$tenantName}</strong></p>
                    <p style="margin:0;">التوقيع: _______________________</p>
                </div>
                <div style="flex:1; font-size:13.5px;">
                    <p style="font-weight:bold; margin:0 0 18px 0;">الشاهد الثاني:</p>
                    <p style="margin:0 0 10px 0;">الاسم: <strong>{$witness2}</strong></p>
                    <p style="margin:0;">رقم الهوية والتوقيع: _____________</p>
                </div>
            </div>

            <!-- محضر استلام المأجور -->
            <div class="page-break-before" style="margin-top:30px; border-top:3px double #2c3e50; padding-top:18px;">
                <h2 style="text-align:center; color:#2c3e50; font-size:18px; margin:0 0 15px 0;">محضر استلام المأجور</h2>
                <p style="font-size:13.5px; margin:0 0 12px 0;">
                    بتاريخ <strong>{$handoverDate}</strong> تم تسليم المأجور إلى المستأجر، وكانت حالته والتجهيزات كما يلي:
                </p>
                <ul style="list-style:none; padding:0; font-size:13.5px; line-height:1.9;">
                    <li>• عدد مفاتيح المنزل: <strong>{$keysCount}</strong></li>
                    <li>• عداد الكهرباء: رقم <strong>{$electricNo}</strong>، القراءة <strong>{$electricReading}</strong></li>
                    <li>• عداد المياه: رقم <strong>{$waterNo}</strong>، القراءة <strong>{$waterReading}</strong></li>
                    <li>• عداد الغاز إن وجد: <strong>{$gasMeter}</strong></li>
                    <li>• حالة الأبواب والنوافذ: <strong>{$doorsState}</strong></li>
                    <li>• حالة الحمامات: <strong>{$bathroomsState}</strong></li>
                    <li>• حالة المطبخ والتجهيزات: <strong>{$kitchenState}</strong></li>
                    <li>• التدفئة/المكيفات: <strong>{$heatingState}</strong></li>
                    <li>• الأثاث الموجود إن كان المنزل مفروشاً: <strong>{$pFurniture}</strong></li>
                    <li>• ملاحظات أخرى: <strong>{$otherNotes}</strong></li>
                </ul>

                <p style="font-size:13.5px; margin:18px 0 25px 0;">ويوقع الطرفان على هذا المحضر باعتباره جزءاً من عقد الإيجار.</p>

                <div style="display:flex; justify-content:space-between; gap:15px;">
                    <div style="flex:1; font-size:13.5px;">
                        <p style="margin:0 0 15px 0;"><strong>المؤجر:</strong> {$ownerName}</p>
                        <p style="margin:0;">التوقيع: _______________________</p>
                    </div>
                    <div style="flex:1; font-size:13.5px;">
                        <p style="margin:0 0 15px 0;"><strong>المستأجر:</strong> {$tenantName}</p>
                        <p style="margin:0;">التوقيع: _______________________</p>
                    </div>
                </div>
            </div>

        </div>
HTML;

        return $html;
    }
}