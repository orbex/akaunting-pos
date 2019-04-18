<template>
    <div class="modal fade" id="clienteModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">{{ $lang.add_customer }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="control-label">
                                    {{ $lang.form.name }}
                                </label>

                                <auto-complete url-remote="pos/customers"
                                               limit-search="1"
                                               ac-prefix="query"
                                               :ac-clear="false"
                                               :ac-placeholder="$lang.search"
                                               @select-item="userSelect"
                                               :ac-search="search"></auto-complete>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="control-label">
                                    {{ $lang.form.email }}
                                </label>
                                <input class="form-control" :placeholder="$lang.form.enter_email"
                                       v-model="clienteForm.email"
                                       name="email" type="text" id="email">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="control-label">
                                    {{ $lang.form.phone }}
                                </label>
                                <input class="form-control" :placeholder="$lang.form.enter_phone"
                                       v-model="clienteForm.phone"
                                       name="phone" type="text" id="phone">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="control-label">
                                    {{ $lang.form.site }}
                                </label>
                                <input class="form-control" :placeholder="$lang.form.enter_site"
                                       v-model="clienteForm.website"
                                       name="website" type="text" id="website">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address" class="control-label">
                                    {{ $lang.form.address }}
                                </label>
                                <textarea class="form-control" :placeholder="$lang.form.enter_address" rows="3" name="address"
                                          cols="50" id="address"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            v-on:click="closeDialog()"
                            data-dismiss="modal">
                        {{ $lang.cancel }}
                    </button>
                    <button type="button"
                            v-on:click="save()"
                            class="btn btn-primary">
                        {{ $lang.save }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AutoComplete from "./AutoComplete";

    export default {
        name: "Customer",
        components: {AutoComplete},
        data() {
            return {
                clienteForm: {},
                error: {},
                search: ''
            }
        },
        props: {
            visible: {
                required: true,
                type: Boolean,
                default: false
            }
        },
        watch: {
            visible: function (newVal, oldVal) {
                if (newVal)
                    $('#clienteModal').modal();
            }
        },
        methods: {
            closeDialog() {
                this.$emit('update:visible', false);
                $('#clienteModal').modal('hide');
            },
            userSelect(item) {
                this.clienteForm = item;
            },
            save() {
                var self = this;

                $.ajax({
                    url: 'pos/customer',
                    type: 'POST',
                    dataType: 'JSON',
                    data: this.clienteForm,
                    success: function (data) {
                        self.closeDialog();
                        self.$emit('checkout-forms', data);
                    }
                });
            }
        },
        mounted() {
            var self = this;

            $('#clienteModal').on('hidden.bs.modal', function () {
                self.$emit('update:visible', false);
            });
        },
    }
</script>

<style scoped>
    .col-md-6 {
        margin-bottom: 20px;
    }
</style>