<template>
    <el-col :sm="counter == true ? 24 : 12" :xs="24" class="pb-3 px-1/2 mb-3"  v-if="!cartStore.cartHasProduct(product)" >
        <div class="img-back d-flex just-center" @click="$router.push({name: 'Product', params: {id: product.id}})" >
            <img @error="placeholderImg" :src="product.cover || placeholderImg" />
        </div>
        <template v-if="isArabic">
            <h3 class="item-title ge-bold text-grey f-24 f-20-m mt-1 mb-1/2 dir-rtl">{{ product.name }}</h3>
        </template>

        <template v-else>
            <h3 class="item-title popp-bold text-grey f-24 f-20-m mt-1 mb-1/2 dir-rtl">{{ product.name }}</h3>
        </template>
        <p class="text-grey f-18 f-16-m ge-medium mt-0 mb-1 dir-rtl ge-medium-numbers" :class="product.description_without_style ? '' : 'mbot-1-3\/4' ">
            {{ product.formatted_price }}
        </p>
        <div class="d-flex just-end my-1" v-if="counter == true">
            <el-input-number  class="qty-product popp-medium" v-model="num" :value="product.quantity" :min="1"
                              @change="$emit('qty',product, num)"/>
        </div>
        <template v-show="product.description_without_style!=null " :class="product.description_without_style!=null ? 'd-block':'' ">
            <el-row>
                <el-col v-show="isOverflowing" :span="3">
                    <Modal btnTitle="المزيد" :modalTitle="product.name" :modalBody="this.product.description_with_style" modalType="info" />
                </el-col>
                <el-col :span="isOverflowing ? 21 : 24 ">
<!--                    <div  class="text-grey f-18 ge-medium mt-0 mb-1 dir-rtl item-des">-->
<!--                    </div>-->
                    <p ref="elmWidth" class="text-grey ge-medium mt-0 mb-1 dir-rtl item-des">
                        {{ product.description_without_style}}

                    </p>
                </el-col>
            </el-row>
        </template>


        <el-button
           class=" add-cart-btn ge-light w-full" type="primary" @click="addToCartBtn"
            >أضف إلى العربة
        </el-button>
<!--        <el-button-->
<!--            v-if="!cartStore.cartHasProduct(product)"-->
<!--            class=" add-cart-btn ge-light w-full" type="primary"-->
<!--            @click="addToCartBtn">أضف إلى العربة-->
<!--        </el-button>-->
<!--        <el-button-->
<!--            v-else-->
<!--            class="remove_back add-cart-btn ge-light w-full" type="primary"-->
<!--            @click="removeFromCart">إزالة من العربة-->
<!--        </el-button>-->
    </el-col>
    <el-col :sm="counter == true ? 24 : 12" :xs="24" class="pb-3 px-1/2 mb-3"  v-else  >
        <div class="img-back d-flex just-center" @click="$router.push({name: 'Product', params: {id: product.id}})">
            <img @error="placeholderImg" :src="product.cover || placeholderImg" />
        </div>
        <template v-if="isArabic">
            <h3 class="item-title ge-bold text-grey f-24 f-20-m mt-1 mb-1/2 dir-rtl">{{ product.name }}</h3>
        </template>

        <template v-else>
            <h3 class="item-title popp-bold text-grey f-24 f-20-m mt-1 mb-1/2 dir-rtl">{{ product.name }}</h3>
        </template>
        <p class="text-grey f-18 f-16-m ge-medium mt-0 mb-1 dir-rtl ge-medium-numbers" :class="product.description_without_style ? '' : 'mbot-1-3\/4' ">
            {{ product.formatted_price }}
        </p>
        <div class="d-flex just-end my-1" v-if="counter == true">
            <el-input-number  class="qty-product popp-medium" v-model="num" :value="product.quantity" :min="1"
                              @change="$emit('qty',product, num)"/>
        </div>


        <template v-show="product.description_without_style!=null " :class="product.description_without_style!=null ? 'd-block':'' ">
            <el-row>
                <el-col v-show="isOverflowing" :span="3">
                    <Modal btnTitle="المزيد" :modalTitle="product.name" :modalBody="this.product.description_with_style" modalType="info" />
                </el-col>
                <el-col :span="isOverflowing ? 21 : 24 ">
                    <!--                    <div  class="text-grey f-18 ge-medium mt-0 mb-1 dir-rtl item-des">-->
                    <!--                    </div>-->
                    <p ref="elmWidth" class="text-grey ge-medium mt-0 mb-1 dir-rtl item-des">
                        {{ product.description_without_style}}

                    </p>
                </el-col>
            </el-row>
        </template>


<!--        <el-button-->
<!--            v-if="!cartStore.cartHasProduct(product)"-->
<!--            class=" add-cart-btn ge-light w-full" type="primary"-->
<!--            @click="addToCartBtn">أضف إلى العربة-->
<!--        </el-button>-->
<!--        <el-button-->
<!--            v-else-->
<!--            class="remove_back add-cart-btn ge-light w-full" type="primary"-->
<!--            @click="removeFromCart">إزالة من العربة-->
<!--        </el-button>-->
        <el-button
            class="remove_back add-cart-btn ge-light w-full" type="primary" @click="removeFromCart"
           >إزالة من العربة
        </el-button>
    </el-col>
</template>

<script>
import {useCartStore} from "../stores/cart";
import {useUserStore} from "../stores/user";
import Modal from "./Modal";
import {ElMessageBox} from "element-plus";
import {ref} from "vue";

export default {
    name: "SimpleProduct",
    components: {Modal},
    setup() {
        const cartStore = useCartStore();
        const userStore = useUserStore();
        return {cartStore, userStore};
    },
    data() {
        return {
            addToCart: false,
            btnBackground: false,
            isOverflowing:false,
            num: ref(this.product.quantity ?? 1),
        }
    },
    mounted() {
            if (this.$refs.elmWidth.offsetWidth < this.$refs.elmWidth.scrollWidth) {
            this.isOverflowing = true;
        }
        let prices = document.querySelectorAll('.ge-medium-numbers');
            for (let i =0 ; i<prices.length;i++){
                prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
            }

    },
    props: {
        product: {},
        counter:Boolean,
    },
    methods: {

        placeholderImg(event){
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        },
        async removeFromCart() {
            document.getElementById('loader').classList.remove('d-none');
            let cartItem = this.cartStore.getCartItemByProduct(this.product);
            console.log(cartItem);
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
            }
            else{
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
            }
        },
        async addToCartBtn() {
            document.getElementById('loader').classList.remove('d-none');
            let response = await this.$http.post(`/api/addToCart/${this.product.id}`, {
                '_token': this.userStore.csrfToken,
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId,
                'quantity': this.num,
            });
            if(response.data.success){
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
            let result = arabic.test(this.product.name);
            console.log(result)
            return result;
        }
    }
}
</script>

<style scoped>

</style>
