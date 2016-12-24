<template>
    <div class="descripcion-presupuesto row">
        <div class="col-xs-1 posicion">
            <i class="fa fa-arrows" ng-click="subir(desc)"></i>
        </div>
        <div class="col-xs-4 col-lg-6">
            <input type="text"
                   v-model="contenido"
                   class="descripcion form-control"
                   @change="changeDesc"
            />
        </div>
        <div class="col-xs-2 col-lg-1">
            <input
                    type="text"
                    v-model="precio"
                    class="precio form-control"
                    change="changePrecio"
            />
        </div>
        <div class="col-xs-2">
            <input type="text" v-model="cantidad" class="cantidad form-control" @change="changeCantidad"/>
        </div>
        <div class="col-xs-2 col-lg-1 sub-total text-right">{{ descripcion.subtotal }} Bs</div>
        <div class="col-xs-1 opciones">
            <i class="fa fa-remove" @dblclick="remove"></i>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import $ from 'jquery'

    //    const DIRECTION_DOWN = 'down'
    //    const DIRECTION_UP = 'up'

    export default {
        props: {
            index: {required: true, type: Number},
            descripcion: {required: true, type: Object},
        },

        data () {
            return {
                contenido: this.descripcion.descripcion,
                precio: this.descripcion.precio,
                cantidad: this.descripcion.cantidad,
            }
        },

        updated() {
//            console.debug('Actualizado componente de item', this.descripcion)
        },

        computed: {
//            content () {
//                return this.descripcion.descripcion
//            },
        },

        methods: {
            changeData () {
                this.data.total = this.getTotal()
                this.$emit('change', this.index, this.data)
            },
            getTotal () {
                return _.toString(this.data.precio).replace(/\D+/g, '')
                        * _.toString(this.data.cantidad).replace(/\D+/g, '')
            },
            remove(){
                console.log(this.index)
                this.$emit('remove', this.index)
            },
            changeDesc () {
                this.$emit('change', this.index, {descripcion: this.contenido})
            },
            changePrecio () {
                this.$emit('change', this.index, {precio: this.precio})
            },
            changeCantidad () {
                this.$emit('change', this.index, {cantidad: this.cantidad})
            }
        },
    }
</script>