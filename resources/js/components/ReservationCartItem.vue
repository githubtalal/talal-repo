<template>
    <div class="desk">
        <el-row>
    <el-col :sm="6" :xs="6">
        <div class="cart-img-back d-flex just-center">
            <img class="cart-img object-contain" :src="product.product.cover" @error="placeholderImg"/>
        </div>
    </el-col>
    <el-col class="d-flex just-center align-center flex-dir-col " :sm="6" :xs="6">
        <template v-if="isArabic">
            <p class="ge-medium text-grey mt-0 mb-1/2 dir-rtl mr-1">{{ product.product.name }}</p>
        </template>

        <template v-else>
            <p class="popp-medium text-grey mt-0 mb-1/2 dir-rtl mr-1">{{ product.product.name }}</p>
        </template>
        <p class="text-grey f-14 popp-medium mt-0 mb-1/2 dir-rtl mr-1 ge-medium-numbers">{{ product.product.formatted_price }}</p>
    </el-col>
    <el-col class="d-flex just-center align-center flex-dir-col" :sm="5" :xs="5">
        <div class="mb-1/2">
            <p class="ge-medium text-grey f-14 m-0">تاريخ الحجز</p>
            <p class="popp-medium text-grey f-14 m-0 ge-medium-numbers">{{ product.checkin }}</p>
        </div>
        <div class="mb-1/2" v-if="product.product.require_end_data">
            <p class="ge-medium text-grey f-14 m-0">تاريخ المغادرة</p>
            <p class="popp-medium text-grey f-14 m-0 ge-medium-numbers">{{ product.checkout }}</p>
        </div>
    </el-col>
    <el-col class="d-flex just-center align-center" :sm="5" :xs="5">
        <p class="text-grey f-18 ge-medium mt-0 mb-1 dir-rtl m-0 ge-medium-numbers">{{ product.formatted_price }} </p>
    </el-col>
    <el-col :xs="2" :sm="2">
        <div class="w-full h-full d-flex just-center align-center" >
<!--            <WarningModal :product="product" modalTitle="هل أنت متأكد؟" modalBody="هل انت متأكد من حذفك للمنتج {{product.product.name}}؟ "/>-->
        </div>
    </el-col>
    <el-col :sm="24" :xs="24">
        <hr>
    </el-col>
        </el-row>
    </div>


<div class="mob">
    <el-row>
    <el-col :xs="11">
        <el-row>
            <el-col :sm="6" :xs="24">
                <div class="cart-img-back d-flex mb-1 just-center">
                    <img class="cart-img object-contain" :src="product.product.cover" @error="placeholderImg"/>
                </div>
            </el-col>
        </el-row>
    </el-col>

    <el-col :xs="11">
        <el-row>
            <el-col class="d-flex just-center align-center flex-dir-col mb-1 " :sm="6" :xs="24">
                <template v-if="isArabic">
                    <p class="ge-medium text-grey mt-0 mb-1/2 dir-rtl mr-1 mr-0-m f-14-m">{{ product.product.name }}</p>
                </template>

                <template v-else>
                    <p class="popp-medium text-grey mt-0 mb-1/2 dir-rtl mr-1 mr-0-m f-14-m">{{ product.product.name }}</p>
                </template>
                <p class="text-grey f-14 f-10-m ge-medium mt-0 mb-1/2 dir-rtl mr-1 mr-0-m ge-medium-numbers">{{ product.product.formatted_price }}</p>
            </el-col>
            <el-col class="d-flex just-center align-center flex-dir-col mb-1" :sm="5" :xs="24">
                <div class="mb-1/2">
                    <p class="ge-medium text-grey f-14 f-10-m m-0 ge-medium-numbers">تاريخ الحجز {{product.checkin }}</p>
                    <p class="popp-medium text-grey f-14 f-10-m m-0"></p>
                </div>
                <div class="mb-1/2" v-if="product.product.require_end_data">
                    <p class="ge-medium text-grey f-14 f-10-m m-0 ge-medium-numbers">تاريخ المغادرة {{ product.checkout }}</p>
                    <p class="popp-medium text-grey f-14 f-10-m m-0"></p>
                </div>
            </el-col>
            <el-col class="d-flex just-center align-center mb-1" :sm="5" :xs="24">
                <p class="text-grey f-18 f-14-m ge-medium mt-0 mb-1 dir-rtl m-0 ge-medium-numbers">{{ product.formatted_price }} </p>
            </el-col>
        </el-row>
    </el-col>
    <el-col :xs="2">
        <div class="w-full h-full d-flex just-center align-center" >
<!--            <WarningModal :product="product" modalTitle="هل أنت متأكد؟" modalBody="هل انت متأكد من حذفك للمنتج "/>-->
        </div>
<!--        <div class="w-full h-full d-flex just-center align-center" @click="removeFromCart(product)">-->
<!--            <img  src="../assets/img/DeleteIcon.svg" class="cursor-pointer trash-img"/>-->
<!--        </div>-->
    </el-col>

    <el-col :sm="24" :xs="24">
        <hr>
    </el-col>
    </el-row>
</div>

</template>

<script>
import {useCartStore} from "../stores/cart";
import {useUserStore} from "../stores/user";
import WarningModal from "./WarningModal";
import {ElMessageBox} from "element-plus";

export default {
    name: "ReservationCartItem",
    components: {WarningModal},
    setup() {
        const cartStore = useCartStore();
        const userStore = useUserStore();
        return {cartStore, userStore};
    },
    props: {
        product: {},
    },
    methods:{
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
        isArabic(){
            let arabic = /[\u0600-\u06FF\u0750-\u077F]/;
            let result = arabic.test(this.product.product.name);
            console.log(result)
            return result;
        },
        placeholderImg(event){
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        }
    },
    mounted(){
        console.log(this.product)
        let prices = document.querySelectorAll('.ge-medium-numbers');
        for (let i =0 ; i<prices.length;i++){
            prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
        }
    },

}
</script>

<style scoped>

</style>
