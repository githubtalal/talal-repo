<template>
    <div class="product-card desk" >
        <el-row>
            <el-col :sm="8" :xs="12" class="dir-rtl px-1\/2-m" @click="$router.push({name: 'Product', params: {id: product.id}})">
                <img @error="placeholderImg" :src="product.cover || placeholderImg" class="reservation-img">
            </el-col>
            <el-col :sm="16" :xs="12" class="dir-rtl ">
                <template v-if="isArabic">
                    <h3 class="item-title ge-bold text-grey f-24 mt-0 mb-1/2">{{ product.name }}</h3>
                </template>

                <template v-else>
                    <h3 class="item-title popp-bold text-grey f-24 mt-0 mb-1/2">{{ product.name }}</h3>
                </template>
                <p class="text-grey f-18 ge-medium mt-0 mb-1 ge-medium-numbers">
                    {{ product.formatted_price }}
                </p>
                <template v-show="product.description_without_style!=null " :class="product.description_without_style!=null ? 'd-block':'' ">
                    <el-row>

                        <el-col :span="isOverflowingDesk ? 21 : 24 ">
                            <!--                    <div  class="text-grey f-18 ge-medium mt-0 mb-1 dir-rtl item-des">-->
                            <!--                    </div>-->
                            <p ref="elmWidthDesk" class="text-grey ge-medium mt-0 mb-1 dir-rtl item-des">
                                {{ product.description_without_style}}

                            </p>
                        </el-col>
                        <el-col v-show="isOverflowingDesk" :span="3">
                            <Modal btnTitle="المزيد" :modalTitle="product.name" :modalBody="this.product.description_with_style" modalType="info" />
                        </el-col>
                    </el-row>
                </template>
                <div class="form">
                    <el-row>
                        <el-col :sm="12" :xs="24">
                            <div class="demo-date-picker">
                                <div class="block">
                                    <label class="ge-medium f-12 text-grey ">تاريخ الحجز</label>
                                    <br>
                                    <el-date-picker
                                        v-model="checkInDate"
                                        class="date-input my-1 popp-medium "
                                        placeholder="mm/dd/yyyy"
                                        type="date"
                                        :clearable="false"
                                        format="YYYY/MM/DD"
                                        value-format="YYYY-MM-DD"
                                        :disabled-date="disabledDate"
                                    />
                                </div>
                            </div>
                        </el-col>
                        <el-col v-if="product.require_hour" :sm="12" :xs="24">
                            <label class="ge-medium f-12 text-grey ">
                                توقيت الحجز
                            </label>
                            <br>
                            <el-time-picker v-model="checkInTime" :disabled-seconds="disabledSeconds"
                                            class="date-input time-input my-1 ge-medium" placeholder="--/--"
                                            format="hh:mm A"
                                            value-format="hh:mm A"
                            />
                        </el-col>

                        <template v-if="product.require_end_data">
                            <el-col :sm="12" :xs="24">
                                <div class="demo-date-picker">
                                    <div class="block">
                                        <label class="ge-medium f-12 text-grey ">تاريخ المغادرة</label>
                                        <br>
                                        <el-date-picker
                                            v-model="checkOutDate"
                                            class="date-input my-1 popp-medium"
                                            placeholder="mm/dd/yyyy"
                                            type="date"
                                            :clearable="false"
                                            format="YYYY/MM/DD"
                                            value-format="YYYY-MM-DD"
                                            :disabled="disableEndDate"
                                            :disabled-date="disabledDateEnd"
                                        />

                                    </div>
                                </div>
                            </el-col>

                            <el-col v-if="product.require_hour" :sm="12" :xs="24">
                                <label class="ge-medium f-12 text-grey ">
                                    توقيت المغادرة
                                </label>
                                <br>
                                <el-time-picker v-model="checkOutTime" :disabled-seconds="disabledSeconds"
                                                class="date-input time-input my-1 popp-medium" placeholder="--/--"
                                                :disabled="disableEndDate" format="hh:mm A"
                                                value-format="hh:mm A"
                                />
                            </el-col>
                        </template>

                    </el-row>
                    <el-button
                        v-if="!cartStore.cartHasProduct(product)"
                        class="add-cart-btn ge-light mt-5" type="primary"
                        @click="addToCartBtn"
                        id="add_btn"
                        :disabled="disableBtn"
                    >أضف إلى العربة
                    </el-button>
                    <el-button
                        v-else
                        class="remove_back add-cart-btn ge-light mt-5" type="primary"
                        @click="removeFromCart">إزالة من العربة
                    </el-button>
                </div>
            </el-col>
        </el-row>
    </div>

    <div class="product-card mob">
        <el-row>
            <el-col :sm="12" :xs="12" class="dir-rtl px-1\/2-m d-flex just-center" @click="$router.push({name: 'Product', params: {id: product.id}})">
                <img @error="placeholderImg" :src="product.cover || placeholderImg" class="reservation-img">
            </el-col>
            <el-col :sm="12" :xs="12" class="dir-rtl d-flex just-center flex-dir-col ">
                <template v-if="isArabic">
                    <h3 class="ge-bold text-grey f-20 mt-0 mb-1/2">{{ product.name }}</h3>
                </template>

                <template v-else>
                    <h3 class="popp-bold text-grey f-20 mt-0 mb-1/2">{{ product.name }}</h3>
                </template>
                <p class="text-grey ge-medium mt-0 mb-1 ge-medium-numbers">
                    {{ product.formatted_price }}
                </p>
                <template v-show="product.description_without_style!=null " :class="product.description_without_style!=null ? 'd-block':'' ">
                    <el-row>
                        <el-col :span="isOverflowingMob ? 21 : 24 ">
                            <!--                    <div  class="text-grey f-18 ge-medium mt-0 mb-1 dir-rtl item-des">-->
                            <!--                    </div>-->
                            <p ref="elmWidthMob" class="text-grey ge-medium mt-0 mb-1 dir-rtl item-des">
                                {{ product.description_without_style}}

                            </p>
                        </el-col>
                        <el-col v-show="isOverflowingMob" :span="3">
                            <Modal btnTitle="المزيد" :modalTitle="product.name" :modalBody="this.product.description_with_style" modalType="info" />
                        </el-col>

                    </el-row>
                </template>
            </el-col>

            <el-col :sm="24" :xs="24">
                <div class="form">
                    <el-row class="dir-rtl">
                        <el-col :sm="12" :xs="12">
                            <div class="demo-date-picker">
                                <div class="block text-right">
                                    <label class="ge-medium f-12 text-grey ">تاريخ الحجز</label>
                                    <br>
                                    <el-date-picker
                                        v-model="checkInDate"
                                        class="date-input my-1 popp-medium"
                                        placeholder="mm/dd/yyyy"
                                        type="date"
                                        :clearable="false"
                                        format="YYYY/MM/DD"
                                        value-format="YYYY-MM-DD"
                                        :disabled-date="disabledDate"
                                    />
                                </div>
                            </div>
                        </el-col>
                        <el-col v-if="product.require_hour" class="text-right" :sm="12" :xs="12">
                            <label class="ge-medium f-12 text-grey ">توقيت الحجز</label>
                            <br>
                            <el-time-picker v-model="checkInTime" :disabled-seconds="disabledSeconds"
                                            class="date-input time-input my-1 ge-medium" placeholder="--/--"
                                            format="hh:mm A"
                                            value-format="hh:mm A"
                            />
                        </el-col>
                        <template v-if="product.require_end_data">
                            <el-col  :sm="12" :xs="12">
                                <div class="demo-date-picker">
                                    <div class="block text-right">
                                        <label class="ge-medium f-12 text-grey ">تاريخ المغادرة</label>
                                        <br>
                                        <el-date-picker
                                            v-model="checkOutDate"
                                            class="date-input my-1 popp-medium"
                                            placeholder="mm/dd/yyyy"
                                            type="date"
                                            :clearable="false"
                                            :disabled="disableEndDate"
                                            format="YYYY/MM/DD"
                                            value-format="YYYY-MM-DD"
                                            :disabled-date="disabledDateEnd"
                                        />

                                    </div>
                                </div>
                            </el-col>
                            <el-col v-if="product.require_hour" class="text-right" :sm="12" :xs="12">
                                <label class="ge-medium f-12 text-grey ">
                                    توقيت المغادرة
                                </label>
                                <br>
                                <el-time-picker v-model="checkOutTime"
                                                :disabled="disableEndDate"
                                                :disabled-seconds="disabledSeconds"
                                                class="date-input time-input my-1 popp-medium" placeholder="--/--"
                                                format="hh:mm A"
                                                value-format="hh:mm A"
                                />

                        </el-col>
                        </template>


                    </el-row>
                    <el-button
                        v-if="!cartStore.cartHasProduct(product)"
                        class="add-cart-btn ge-light mt-1-m w-full" type="primary"
                        @click="addToCartBtn"
                        id="add_btn"
                        :disabled="disableBtn"
                    >أضف إلى العربة
                    </el-button>
                    <el-button
                        v-else
                        class="remove_back add-cart-btn ge-light mt-1-m w-full" type="primary"
                        @click="removeFromCart">إزالة من العربة
                    </el-button>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import {useCartStore} from "../stores/cart";
import {useUserStore} from "../stores/user";
import {ElMessageBox} from "element-plus";
import Modal from "./Modal.vue";

export default {
    name: "ReservationProduct",
    components: {Modal},
    setup() {
        const cartStore = useCartStore();
        const userStore = useUserStore();
        return {cartStore, userStore};
    },
    props: {
        product: {},
    },
    data() {
        return {
            checkInDate: '',
            checkInTime: '',
            checkOutDate: '',
            checkOutTime: '',
            title: '',
            price: '',
            img: [],
            addToCart: false,
            btnBackground: false,
            reservationType: '',
            time: '',
            disableEndDate: true,
            relatedCartItem: {},
            isOverflowingDesk:false,
            isOverflowingMob:false,

        }
    },
    mounted() {
        this.relatedCartItem = this.cartStore.getCartItemByProduct(this.product)
        if (this.relatedCartItem) {
            this.checkInDate = this.relatedCartItem.checkin_date
            this.checkInTime = this.relatedCartItem.checkin_time
            this.checkOutDate = this.relatedCartItem.checkout_date
            this.checkOutTime = this.relatedCartItem.checkout_time
        }
        if (this.$refs.elmWidthDesk.offsetWidth < (this.$refs.elmWidthDesk.scrollWidth )) {
            console.log('test')
            this.isOverflowingDesk = true;
            this.isOverflowingMob = true;
        }
        if (this.$refs.elmWidthMob.offsetWidth < (this.$refs.elmWidthMob.scrollWidth )) {
            console.log('test mob')
            this.isOverflowingMob = true;
            this.isOverflowingDesk = true;

        }
        let prices = document.querySelectorAll('.ge-medium-numbers');
        for (let i =0 ; i<prices.length;i++){
            prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
        }
    },
    computed: {
        disableBtn() {
            if (!this.checkInDate == '') {
                this.disableEndDate = false
                if (this.product.require_end_data && !this.checkOutDate == '') {
                    return false;
                } else if (!this.product.require_end_data && !this.time == '') {
                    return false;
                }
            } else {
                return true;
            }
        }
    },
    methods: {
        placeholderImg(event) {
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        },
        disabledDate(time) {
            return time.getTime() < Date.now()
        },
        disabledDateEnd(time) {
            return time.getTime() <= Date.parse(this.checkInDate)
        },
        async addToCartBtn() {
            document.getElementById('loader').classList.remove('d-none');
            let response = await this.$http.post(`/api/addToCart/${this.product.id}`, {
                checkin: this.checkInDate,
                checkin_time: this.checkInTime,
                checkout: this.checkOutDate,
                checkout_time: this.checkOutTime,
                '_token': this.userStore.csrfToken,
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId,
            });
            if (response.data.success) {
                document.getElementById('loader').classList.add('d-none');
                this.cartStore.initCart(response.data);
            } else {
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
            }
        },
        async removeFromCart() {
            document.getElementById('loader').classList.remove('d-none');
            let cartItem = this.cartStore.getCartItemByProduct(this.product);
            if (!cartItem) return;
            let response = await this.$http.get(`/api/removeFromCart/${cartItem.id}`, {
                params: {
                    'user_id': this.userStore.userId,
                    'store_id': this.userStore.storeId,
                }
            });
            if (response.data.success) {
                document.getElementById('loader').classList.add('d-none');
                this.cartStore.removeItem(cartItem);
                this.checkInDate = '';
                this.checkOutDate = '';
                this.time = '';
                this.disableEndDate = true;
            } else {
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
            }


        },
        makeRange(start, end) {
            const result = []
            for (let i = start; i <= end; i++) {
                result.push(i)
            }
            return result
        },
        disabledSeconds(hour, minute) {
            if (hour <= 24 && minute <= 60) {
                return this.makeRange(1, 59)
            }
        },
        isArabic() {
            let arabic = /[\u0600-\u06FF\u0750-\u077F]/;
            let result = arabic.test(this.product.name);
            console.log(result)
            return result;
        }
    }
}
</script>

<style scoped>

</style>
