<template>
    <div id="presupuesto" class="overflow" style="height:auto;">
        <div class="row presupuesto-headers">
            <div class="col-xs-2">Título</div>
            <div class="col-xs-10">
                <input type="text" v-model="titulo" class="form-control"/>
            </div>
        </div>

        <div class="row presupuesto-headers">
            <div class="col-xs-1">Orden</div>
            <div class="col-xs-4 col-lg-6 text-center">Mano de Obra</div>
            <div class="col-xs-2 col-lg-1 text-center">Bs</div>
            <div class="col-xs-2">Cantidad</div>
            <div class="col-xs-2 col-lg-1 text-center">Total Bs</div>
            <div class="col-xs-1">Remover</div>
        </div>

        <Draggable
                :list="items"
                :options="{handle: '.posicion', animation: 150}"
                @update="save"
        >
            <transition-group>
                <Descripcion v-for="(item, index) in items"
                             :descripcion="item"
                             :key="index"
                             :index="index"
                             @change="changeItemData"
                             @remove="removeItem"
                />
            </transition-group>
        </Draggable>

        <div class="row prespuesto-headers">
            <div class="col-xs-7">
                <a class="btn btn-default" @click="addItem">+ Linea</a>
            </div>
            <div class="col-xs-2">TOTAL :</div>
            <div class="col-xs-2 text-right">{{ total }} Bs</div>
            <div class="col-xs-1"></div>
        </div>
        <!--<tfoot>-->
        <!--<tr>-->
        <!--<td colspan="2">-->
        <!--{% if presupuesto.id is not null %}-->
        <!--<a href="{{ path(" presupuesto_export",{id : presupuesto.id}) }}" target="_blank"-->
        <!--class="btn">Imprimir</a>-->
        <!--{% else %}-->
        <!--<a href="#" target="_blank" class="btn disabled">Imprimir</a>-->
        <!--{% endif %}-->
        <!--<input class="btn pull-right guardar" value="Guardar" type="submit"/>-->
        <!--</td>-->
        <!--<td colspan="2" style="text-align: right;padding-right: 20px;vertical-align: middle">-->
        <!--<b>TOTAL :</b>-->
        <!--</td>-->
        <!--<td colspan="2" style="text-align: center;font-size: 18px;vertical-align: middle"-->
        <!--class="total">-->
        <!--{{ total }} Bs-->
        <!--</td>-->
        <!--</tr>-->
        <!--</tfoot>-->
    </div>
</template>

<script>
    import Vue from 'vue'
    import Draggable from 'vuedraggable'
    import Descripcion from 'Presupuesto/Descripcion.vue'
    import _ from 'lodash'
    import axios from 'axios'

    function extractNumber(string) {
        return Number(_.toString(string).replace(/\D+/g, ''))
    }

    export default {
        props: {
            apiUrl: {required: true, type: String},
        },

        data () {
            return {
                titulo: null,
                items: [],
            }
        },

        created () {
            this.getManosDeObra()
        },

        computed: {
            total () {
                return _.sumBy(this.items, item => Number(item.subtotal))
            }
        },

        methods: {
            preparePostData(data) {

                data.descripciones = data.descripciones.map((desc, index) => {
                    return Object.assign({},desc, {
                        subtotal: _.toString(desc.subtotal),
                        posicion: index + 1,
                    })
                })

                return data
            },
            loadFromData (data) {
                this.id = data.id
                this.titulo = data.titulo || ''

                if (data.descripciones) {
                    this.items = data.descripciones
                }
            },
            getManosDeObra () {
                axios.get(this.apiUrl).then(({data}) => this.loadFromData(data))
            },
            save () {
                axios.post(this.apiUrl, this.preparePostData({
                    titulo: this.titulo,
                    descripciones: this.items,
                })).then(({data}) => this.loadFromData(data))
            },
            addItem () {
                this.items.push({
                    id: null,
                    posicion: null,
                    descripcion: null,
                    precio: null,
                    cantidad: null,
                    subtotal: null,
                })
                this.save()
            },
            changeItemData(index, data) {
                let desc = Object.assign({}, this.items[index], data);
                desc.subtotal = extractNumber(desc.precio) * extractNumber(desc.cantidad)

                Vue.set(this.items, index, desc)
                console.log('Actualizando', index, data, this.items[index])
                this.save()
            },
            removeItem(index) {
                let items = _.clone(this.items)
                console.log(index)
                items.splice(index, 1)

                this.items = items

                this.save()
            },
        },

        components: {Descripcion, Draggable},
    }
</script>