<template>
    <Title title="المعلومات الشخصية" back="true"/>
    <v-form @submit.prevent="onSubmit" ref="form" :validation-schema="validationSchema" v-slot="{ isSubmitting }">
        <div class="container dir-rtl">
            <el-row>
                <el-col :sm="12" :xs="24" class="px-1">
                    <label class="ge-medium f-14 text-grey ">
                        الاسم
                    </label>
                    <br>
                    <v-field type="text" label="الأسم" name="firstname" required v-model="full_name"
                             class="form-input my-1">
                    </v-field>
                    <error-message class="text-error f-12" name="firstname"></error-message>
                </el-col>
                <el-col :sm="12" :xs="24" class="px-1">
                    <label class="ge-medium f-14 text-grey ">رقم الجوال</label>
                    <br>
                    <v-field type="text" name="phonenumber" label="رقم الجوال" required v-model="phoneNumber"
                             class="form-input my-1">
                    </v-field>
                    <error-message  class="text-error f-12" name="phonenumber"></error-message>
                </el-col>
                <el-col v-if="checkout_steps.steps && checkout_steps.steps.governorate === 'enabled'" :sm="12" :xs="24"
                        class="px-1 position-relative">
                    <label class="ge-medium f-12 text-grey ">المدينة.</label>
                    <br>
                    <v-field required name="city" v-model="city" as="select" class=" w-full form-input select my-1">
                        <option :value="'دمشق'">دمشق</option>
                        <option :value="'ريف دمشق'">ريف دمشق</option>
                        <option :value="'اللاذقية'"> اللاذقية</option>
                        <option :value="'حمص'">حمص</option>
                        <option :value="'حماة'">حماة</option>
                        <option :value="'درعا'">درعا</option>
                        <option :value="'القنيطرة'">القنيطرة</option>
                        <option :value="'السويداء'">السويداء</option>
                        <option :value="'الرقة'">الرقة</option>
                        <option :value="'الحسكة'">الحسكة</option>
                        <option :value="'دير الزور'">دير الزور</option>
                        <option :value="'إدلب'">إدلب</option>
                        <option :value="'حلب'">حلب</option>
                        <option :value="'طرطوس'">طرطوس</option>
                    </v-field>
                    <div class=" select-icon">
                        <font-awesome-icon icon="fa-solid fa-chevron-down"></font-awesome-icon>
                    </div>
                    <error-message class="text-error" name="city"></error-message>
                </el-col>
                <el-col v-if="checkout_steps.steps && checkout_steps.steps.address === 'enabled'" :sm="24" :xs="24"
                        class="px-1">
                    <label class="ge-medium f-12 text-grey ">العنوان.</label>
                    <br>
                    <v-field required type="text" name="address" v-model="address" class="form-input my-1">
                    </v-field>
                    <error-message class="text-error" name="address"></error-message>
                </el-col>
                <el-col v-if="checkout_steps.steps && checkout_steps.steps.notes === 'enabled'" :sm="24" :xs="24"
                        class="px-1">
                    <label class="ge-medium f-14 text-grey ">
                        {{ checkout_steps.notes || 'ملاحظات' }}
                    </label>
                    <br>
                    <v-field type="text" name="notes" v-model="notes" class=" form-input my-1">
                    </v-field>
                    <error-message class="text-error f-12" name="notes"></error-message>

                </el-col>
                <template v-for="(additional_question, idx) in additional_questions">
                    <el-col v-if="additional_question"  :sm="24" :xs="24"
                            class="px-1">
                        <label class="ge-medium f-14 text-grey">
                            {{ additional_question  }}
                        </label>
                        <br>
                        <v-field type="text" :name="'additional_questions_values' + idx" v-model="additional_questions_values[idx]" class=" form-input my-1">
                        </v-field>
                        <error-message class="text-error f-12" :name="'additional_questions_values' + idx"></error-message>

                    </el-col>
                </template>

            </el-row>
        </div>
        <Title title="التفاصيل"/>
        <div class="container dir-rtl">
            <el-row>
                <el-col :sm="12" :xs="12">
                    <p class="text-grey ge-medium text-center">اسم المنتج</p>
                </el-col>
                <el-col :sm="12" :xs="12">
                    <p class="text-grey ge-medium text-center">السعر</p>
                </el-col>
                <DetailsItem :cartItems="cartStore.cartItems"/>
                    <template v-for="(totalLabel, totalKey) in totals" :key="totalKey">
                        <el-col :sm="24" :xs="24" class="my-1" v-if="cartStore.cart[totalKey] && cartStore.cart[totalKey] > 0">
                            <div class="total-div">

                                <el-row class="h-full">
                                    <el-col :sm="6" :xs="11" class="d-flex align-center just-center">
                                        <p class="ge-medium text-grey f-18 m-0 dir-rtl f-12-m">
                                            {{ totalLabel }}
                                        </p>
                                    </el-col>
                                    <el-col :sm="11" :xs="0">

                                    </el-col>
                                    <el-col :sm="7" :xs="13" class="d-flex align-center just-center f-12-m">
                                        <p class="text-red ge-medium m-0 dir-rtl ge-medium-numbers">
                                            {{ cartStore.cart['formatted_' + totalKey] }}
                                        </p>
                                    </el-col>
                                </el-row>
                            </div>
                        </el-col>
                    </template>

            </el-row>


        </div>
        <Title title="طرق الدفع"/>
        <div class="container dir-rtl">
            <el-row>
                <!--        <el-col  :xs="24" :sm="24">-->
                <!--          <div class="payment_method border-blue my-1">-->
                <!--            <img src="../assets/img/FatoraLogo2.svg" style="width: 15%"/>-->
                <!--          </div>-->
                <!--        </el-col>-->
                <el-col :sm="24" :xs="24">

                    <div class="demo-collapse">
                        <el-collapse accordion>
                            <el-collapse-item v-for="method in payment_methods"
                                              :key="method"
                                              :class="method.key"
                                              @click="methodPicked = method; selectedPaymentMethod=method.key">
                                <template #title>
                                    <v-field name="payment_method" required :value="method.key" type="radio"
                                           @change="methodPicked=method"
                                           v-model="selectedPaymentMethod" ref="radioButton"/>
                                    <template v-if="method.logo">
                                        <img style="width: 13%" :src="method.logo"/>
                                        <div style="width: 77%"></div>
                                    </template>
                                    <template v-else>
                                        <p class="m-0 w-90">{{ method.label }}</p>
                                    </template>
                                </template>
                                <div v-html="method.desc"></div>
                            </el-collapse-item>
                        </el-collapse>
                        <error-message class="text-error" name="payment_method"></error-message>
                    </div>

                    <el-col :sm="12" :xs="24" class="px-1 py-1" v-for="extra_field in userExtraFields">
                        <label class="ge-medium f-12 text-grey ">
                            {{ extra_field.label }}
                        </label>
                        <br>
                        <v-field
                            :type="extra_field.type"
                            v-model="additional_fields[extra_field.name]"
                            :placeholder="extra_field.placeholder"
                            :name="extra_field.name"
                            class="form-input my-1">
                        </v-field>
                        <error-message class="text-error" :name="extra_field.name"></error-message>
                    </el-col>

                    <!--                    <div v-for="method in payment_methods" :key="method"-->
                    <!--                         class="payment_method border-red my-1 d-flex just-center align-center mb-1 "-->
                    <!--                         @click="open(method)">-->
                    <!--                        <template v-if="!method.logo == null">-->
                    <!--                            <img :src="method.logo"/>-->
                    <!--                        </template>-->
                    <!--                        <button type="submit" class="text-grey ge-medium payment-but">-->
                    <!--                            {{ method.label }}-->
                    <!--                        </button>-->
                    <!--                    </div>-->
                </el-col>
                <el-col :xs="24" :sm="24">
                    <div class="d-flex align-center">
                        <button @click="open(this.methodPicked)" class="bot-2">إتمام عملية الشراء</button>
                    </div>
                </el-col>

            </el-row>
        </div>
    </v-form>
</template>

<script>
import Title from "../components/Title.vue";
import DetailsItem from "../components/DetailsItem.vue";
import {ElMessage, ElMessageBox} from 'element-plus'
import {useUserStore} from "../stores/user";
import {useCartStore} from "../stores/cart";
import * as yup from 'yup';
import * as VeeValidate from "vee-validate";
import {library} from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

import {faChevronDown} from '@fortawesome/free-solid-svg-icons'
import {onMounted, ref} from "vue";

library.add(faChevronDown)
import * as Yup from 'yup';

// Yup.setLocale({
//     mixed: {
//         required: 'الحقل مطلوب',
//     },
// });

export default {
    name: "Checkout",
    components: {
        DetailsItem, Title,
        VForm: VeeValidate.Form,
        VField: VeeValidate.Field,
        ErrorMessage: VeeValidate.ErrorMessage,
    },
    setup() {
        const userStore = useUserStore();
        const cartStore = useCartStore();
        return {userStore, cartStore};
    },

    data() {
        return {
            cart: {},
            cart_items: [],
            payment_methods: [],
            full_name: '',
            checkout_steps: {},
            phoneNumber: '',
            city: '',
            address: '',
            notes: '',
            schema: yup.object().shape(),
            methodPicked: '',
            additional_fields: {},
            selectedPaymentMethod: '',
            totals: {
                'sub_total': 'المجموع الجزئي',
                'fees_total': 'أجور الخدمة',
                'tax_total': 'الضريبة',
                'total': 'المجموع الكلي',
            },
            additional_questions: [],
            additional_questions_values: {},
        }
    },
    async mounted() {
        console.log(this.cart);
        let response = await this.$http.get('/api/get-checkout', {
            params: {
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId
            }
        });
        if (!response.data.success)
            this.$router.push({name:'Categories'});
        this.cart = response.data.cart;
        this.payment_methods = response.data.payment_methods;
        this.cart_items = response.data.cart_items;
        this.checkout_steps = response.data.flow;
        this.additional_questions = response.data.additional_questions;
        window.addEventListener('load', function () {
            document.getElementsByClassName('cod')[0].childNodes[1].remove()
            document.getElementsByClassName('cod')[0].childNodes[0].childNodes[0].childNodes[4].remove()
        })

    },
    methods: {
        onSubmit(values) {

            return new Promise(resolve => {

                setTimeout(() => {
                    resolve(JSON.stringify(values, null, 2));
                }, 2000);
            })
        },
        isRequired(value) {
            if (value && value.trim()) {
                return true;
            }
            return 'This is required';
        },
        async open(method) {
            try {
                document.getElementById('loader').classList.remove('d-none');
                const formValid = await this.$refs.form.validate();

                if (!formValid.valid) {
                    document.getElementById('loader').classList.add('d-none');
                    console.log(formValid);
                    ElMessageBox({
                        title: 'المعلومات غير صحيحة',
                        message: " الرجاء التحقق من المعلومات",
                        showCancelButton: 'العودة',
                        confirmButtonText: false,
                        type: 'error',
                    })
                    return
                }
                let splitted = this.full_name.split(' ');
                let first_name = splitted[0];
                let last_name = splitted.length > 1 ? splitted[1] : '';
                let response = await this.$http.post('/api/checkout', {
                    'method': this.selectedPaymentMethod,
                    customer_phone_number: this.phoneNumber,
                    customer_first_name: first_name,
                    customer_last_name: last_name,
                    customer_address: this.address,
                    customer_governorate: this.city,
                    notes: this.notes,
                    user_id: this.userStore.userId,
                    store_id: this.userStore.storeId,
                    additional_user_fields: this.additional_fields,
                    additional_question1: this.additional_questions_values[0],
                    additional_question2: this.additional_questions_values[1]
                });
                if (response.data.success) {
                    document.getElementById('loader').classList.add('d-none');
                    response = response.data;
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        this.cartStore.afterCheckout();
                        ElMessageBox({
                            title: 'شكرا لك',
                            message: `قد تم تسجبل الطلب بنجاح رقم الطلب الخاص بكم  ${response.order.id}`
                                + (response.additional_message ? `<br>${response.additional_message}` : ''),
                            showCancelButton: false,
                            confirmButtonText: 'العودة إلى صفحة الرئيسة',
                            type: 'success',
                            dangerouslyUseHTMLString: true
                        })
                            .then(() => {
                                ElMessage({
                                    type: 'success',
                                    message: ' شكرا لإستخدامكن إي كارت',
                                })
                                this.$router.push({name:'Categories'})
                            })
                    }
                } else {
                    ElMessageBox({
                        title: 'خطأ',
                        message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                        showCancelButton: 'العودة',
                        confirmButtonText: false,
                        type: 'error',
                    })
                    document.getElementById('loader').classList.add('d-none');
                }
            } catch (e) {
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
                document.getElementById('loader').classList.add('d-none');
            }
        }
    },

    computed: {
        validationSchema() {
            let schema = {
                firstname: yup.string().required('الأسم مطلوب'),
                phonenumber: yup.string().required('رقم الجوال مطلوب'),
                payment_method: yup.string().required('طريقة الدفع مطلوبة'),
            }
            if (this.checkout_steps.steps && this.checkout_steps.steps.notes === 'enabled')
                schema['notes'] = yup.string().required('هذا الحقل مطلوب')
            if (this.checkout_steps.steps && this.checkout_steps.steps.governorate === 'enabled')
                schema['city'] = yup.string().required()

            return yup.object().shape(schema);
        },
        userExtraFields() {
            let selectedPaymentMethod = this.methodPicked;
            let extra_fields = []
            if (!selectedPaymentMethod) return extra_fields;
            this.payment_methods.forEach((method) => {
                if (method.key === selectedPaymentMethod.key)
                    extra_fields = method.extra_fields;
            });
            return extra_fields;
        }
    }
}

</script>

<style scoped>

</style>
