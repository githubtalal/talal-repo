<template>
    <div class="desk">
        <el-row>
            <el-col :sm="6" :xs="6">
                <div class="cart-img-back d-flex just-center">
                    <img class="cart-img object-contain" :src="product.product.cover " @error="placeholderImg"/>
                </div>
            </el-col>
            <el-col class="d-flex just-center align-center flex-dir-col " :sm="6" :xs="6">
                <template v-if="isArabic">
                    <p class="ge-medium  text-grey mt-0 mb-1/2 dir-rtl mr-1">{{ product.product.name }}</p>
                </template>

                <template v-else>
                    <p class="popp-medium text-grey mt-0 mb-1/2 dir-rtl mr-1">{{ product.product.name }}</p>
                </template>
                <p class="text-grey f-14 ge-medium mt-0 mb-1 dir-rtl mr-1 ge-medium-numbers">{{ product.product.formatted_price }}</p>
            </el-col>
            <el-col class="d-flex just-center align-center" :sm="5" :xs="5">
                <el-input-number class="qty popp-medium" v-model="num" :value="product.quantity" :min="1"
                                 @change="$emit('qty',product, num)"/>
            </el-col>
            <el-col class="d-flex just-center align-center" :sm="5" :xs="5">
                <p class="text-grey f-18 ge-medium m-0 dir-rtl ge-medium-numbers">{{ product.formatted_price }} </p>
            </el-col>
            <el-col :xs="2" :sm="2">
                <div class="w-full h-full d-flex just-center align-center" >
<!--                    <WarningModal :product="product" modalTitle="هل أنت متأكد؟" modalBody="هل انت متأكد من حذفك للمنتج "/>-->
                </div>
<!--                <div class="w-full h-full d-flex just-center align-center" @click="removeFromCart(product)">-->
<!--                    <img src="../assets/img/DeleteIcon.svg" class="cursor-pointer"/>-->
<!--                </div>-->
            </el-col>
            <el-col :sm="24" :xs="24">
                <hr>
            </el-col>
        </el-row>
    </div>

    <div class="mob">
        <el-row>
            <el-col :xs="11">
                <div class="cart-img-back d-flex just-center">
                    <img class="cart-img object-contain" :src="product.product.cover " @error="placeholderImg"/>
                </div>
            </el-col>
            <el-col :xs="11">
                <el-row>
                    <el-col class="d-flex just-center align-center flex-dir-col " :sm="6" :xs="24">
                        <template v-if="isArabic">
                            <p class="ge-medium f-14-m text-grey mt-0 mb-1/2 dir-rtl mr-1 mr-0-m">
                                {{ product.product.name }}</p>
                        </template>

                        <template v-else>
                            <p class="popp-medium f-14-m text-grey mt-0 mb-1/2 dir-rtl mr-1 mr-0-m">
                                {{ product.product.name }}</p>
                        </template>
                        <p class="text-grey f-14 ge-medium mt-0 mb-1 dir-rtl mr-1 f-10-m ge-medium-numbers">
                            {{ product.formatted_price }}</p>
                    </el-col>
                    <el-col class="d-flex just-center align-center" :sm="5" :xs="24">
                        <el-input-number class="qty popp-medium" v-model="num" :value="product.quantity" :min="1"
                                         @change="$emit('qty',product, num)"/>
                    </el-col>
                    <el-col class="d-flex just-center align-center" :sm="5" :xs="24">
                        <p class="text-grey f-18 f-14-m ge-medium m-0 dir-rtl ge-medium-numbers">{{ product.formatted_price }} </p>
                    </el-col>
                </el-row>
            </el-col>
            <el-col :xs="2" :sm="2">
                <div class="w-full h-full d-flex just-center align-center" >
<!--                    <WarningModal :product="product" modalTitle="هل أنت متأكد؟" modalBody="هل انت متأكد من حذفك للمنتج  "/>-->
                </div>
<!--                <div class="w-full h-full d-flex just-center align-center" @click="removeFromCart(product)">-->
<!--                    <img src="../assets/img/DeleteIcon.svg" class="cursor-pointer trash-img"/>-->
<!--                </div>-->
            </el-col>
            <el-col :sm="24" :xs="24">
                <hr>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import {ref} from "vue";
import {useCartStore} from "../stores/cart";
import {useUserStore} from "../stores/user";
import WarningModal from "./WarningModal";
import {ElMessageBox} from "element-plus";

export default {
    name: "CartItem",
    components: {WarningModal},
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
            num: ref(this.product.quantity),
            basePrice: this.product.product.price,
        }
    },
    methods: {
        async removeFromCart(cartItem) {
            document.getElementById('loader').classList.remove('d-none');
            if (!cartItem) return;
            let response = await this.$http.get(`/api/removeFromCart/${cartItem.id}`, {
                params: {
                    'user_id': this.userStore.userId,
                    'store_id': this.userStore.storeId,
                }
            });
            if (response.data.success) {
                document.getElementById('loader').classList.add('d-none');
                this.cartStore.initCart(response.data);
            }else{
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
            }
        },
        placeholderImg(event) {
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        },
        handleChange(value) {
            this.product.product.price = this.basePrice * value
        },
        isArabic() {
            let arabic = /[\u0600-\u06FF\u0750-\u077F]/;
            let result = arabic.test(this.product.product.name);
            console.log(result)
            return result;
        }
    },
    mounted() {
        console.log(this.product);
        let prices = document.querySelectorAll('.ge-medium-numbers');
        for (let i =0 ; i<prices.length;i++){
            prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
        }
    },
}
</script>

<style scoped>

</style>
