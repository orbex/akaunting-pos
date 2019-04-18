<template>
    <div>
        <div v-if="checkoutSuccess" class="alert alert-success">
            {{ $lang.sale_completed }}
            <button type="button" class="close" @click="checkoutSuccess=false">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="row">
            <div class="col-md-5">
                <form class="ng-pristine ng-valid ng-valid-maxlength">
                    <div class="form-group">
                        <label>{{ $lang.add_cart }}</label>

                        <search-produts @product="product"></search-produts>
                    </div>

                    <div class="form-group">
                        <best-sellers @product="product"></best-sellers>
                    </div>
                </form>
            </div>

            <div class="col-md-7 pos-produt-carrinho">
                <div class="text-center" id="carEmpty" style="color: #b3b3b3;"
                     v-show="!products.length">
                    <i class="fa fa-shopping-cart" style="font-size: 150px;"></i>
                    <br>

                    <p style="font-size: 25px; padding-top: 10px;">
                        {{ $lang.add_products_sale }}
                    </p>
                </div>

                <div class="table-responsive">
                    <table id="tableProducts"
                           v-show="products.length"
                           class="table table-hover table-bordered table-pos table-responsive">
                        <thead>
                        <tr>
                            <th class="col-md-5">{{ $lang.product }}</th>
                            <th class="col-md-2">{{ $lang.price }}</th>
                            <th class="col-md-2">{{ $lang.amount }}</th>
                            <th class="col-md-2 text-center">{{ $lang.subtotal }}</th>
                            <th class="col-md-1"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(p, index) in products"
                            :key="p.id">
                            <td class="col-5 ng-binding">{{ index + 1 }}. {{ p.name }}</td>
                            <td class="col-2 ng-binding">
                                {{ p.sale_price | toCurrency }}
                            </td>
                            <td class="col-2">
                                <input type="number" v-model="p.quantity" v-on:keyup="getTotal()"
                                       class="form-control input-sm">
                            </td>
                            <td class="col-2 text-center ng-binding"> {{ p.quantity * p.sale_price | toCurrency  }}
                            </td>
                            <td class="align-right">
                                <a href="javascript:;" class="btn btn-sm btn-default"
                                    v-on:click="removeProduct(index)">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <customer :visible.sync="dialogCustomer" @select-customer="customerSelect"></customer>

        <checkout :visible.sync="dialogCheckout" @checkout-form="checkoutForm" :data-form="form"></checkout>

        <div class="box-footer pos-footer ng-scope">
            <div class="row">
                <div class="col-md-6 text-left">
                    <button type="button" v-on:click="addCustomer()" class="btn btn-primary btn-block btn-flat"
                            style="height:50px;">
                        <span v-show="!form.customer">
                            {{ $lang.add_customer }}
                        </span>

                        <span v-if="form.customer" class="ng-binding ng-hide">
                            {{ form.customer.name }}
                        </span>
                    </button>
                </div>

                <div class="col-md-6 text-right">
                    <button type="button" v-on:click="checkoutDialog()" :disabled="products.length <= 0"
                            class="btn btn-success btn-block btn-flat" style="height:50px;">

                        <span>{{ $lang.receiver_account }}</span>

                        <span style="padding-left: 20px;" id="invoice">{{ form.amount | toCurrency }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BestSellers from "./BestSellers";
    import Customer from "./Customer";
    import Checkout from "./Checkout";

    export default {
        name: "Pos",
        components: {BestSellers, Customer, Checkout},
        data: function () {
            return {
                products: [],
                form: {
                    amount: 0
                },
                dialogCustomer: false,
                dialogCheckout: false,
                checkoutSuccess: false
            }
        },
        methods: {
            product(item) {

                for(let i = 0; i < this.products.length; i++){
                    if(item.id == this.products[i].id){
                        this.products[i].quantity = parseInt(this.products[i].quantity) + 1;
                        this.getTotal();
                        return;
                    }
                }

                this.products.push({
                    id: item.id,
                    name: item.name,
                    quantity: 1,
                    sale_price: item.sale_price,
                });

                this.getTotal();
            },
            removeProduct(index){
                this.products.splice(index, 1);
                this.getTotal();
            },

            customerSelect(item){
                this.form.customer = item;
            },

            checkoutForm(item){
                this.form = {amount: 0};
                this.products = [];

                this.checkoutSuccess = true;
            },

            getTotal(){
                this.form.amount = 0;

                for(let i = 0; i < this.products.length; i++){
                    this.form.amount += this.products[i].quantity * this.products[i].sale_price;
                }
            },

            addCustomer(){
                this.dialogCustomer = true;
            },

            checkoutDialog(){
                this.form.producs = this.products;
                this.dialogCheckout = true;
            }
        }
    }
</script>

<style scoped>
    .input-pdv {
        height: 38px;
        margin-bottom: 30px;
    }

    .box-footer.pos-footer {
        width: 100%;
        position: fixed;
        bottom: 0px;
        left: 0px;
        padding-left: 250px;
    }

    .sidebar-collapse .box-footer.pos-footer {
        padding-left: 70px;
    }

    #tableProducts{
        background: #fff;
        margin-top: 23px;
    }
</style>