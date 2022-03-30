<template>
<div class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full">
    <div class="mb-8">
        Новый отчет
    </div>
    <div class="space-y-4">
        <div class="flex h-6">
            <p class="w-1/6">Название<span class="text-red-400">*</span></p>
            <input type="text" class="w-4/6 border-b-2 w-full pb-1">
            <p class="mr-3">Категория</p>
            <div class="border-b-2">
                <select class="bg-gray-100 rounded-full p-1 -mt-2">
                    <option>
                        по сотрудникам
                    </option>

                    <option>
                        по клиентам
                    </option>

                    <option>
                        по товарам
                    </option>
                </select>
            </div>
        </div>
        <div class="flex">
            <p class="w-1/6">Описание<span class="text-red-400">*</span></p>
            <div class="flex border-b-2 w-4/6 w-full justify-end"><input type="file" id="file" ref="file" v-on:change="handleFileUpload()" class="opacity-0 w-full pb-1"><img src="/img/download.svg" /></div>
        </div>

        <div class="flex">
            <p class="w-1/6">Дата<span class="text-red-400">*</span></p><input type="text" class="w-4/6 border-b-2 w-full pb-1">
        </div>
        <div class="flex">
            <p class="w-1/6">Доступ<span class="text-red-400">*</span></p>
            <div class="flex w-full border-b-2 justify-between">
                <select class="bg-gray-100 rounded-full p-1 ">
                    <option>
                        виден всем
                    </option>

                    <option>
                        только мне
                    </option>

                </select>

            </div>

        </div>
        <div>
            <div class="w-full flex">
                <div class="lg:w-1/4">
                    <p class="font-medium leading-6">Заполните поле
                        <span class="text-red-400">*</span>
                    </p>
                </div>
                <div class="lg:w-3/4 flex justify-end">

                    <button class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import Checkbox from '@/Shared/Checkbox2'
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'
import axios from "axios";

export default {
    computedDate() {
        return date.toISOString().substring(0, 10)
    },
    name: 'CreateReport',
    metaInfo: {
        title: 'Новый отчет'
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        Datepicker,
        Checkbox
    },
    layout: Layout,
    props: {
        type: String,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                user: 1,
            }),
        }
    },
    created() {
        // axios.get('/reports/create')
        //   .then(response => {
        //     this.users = response.data.users 
        // })
    },
    methods: {
        store() {
            this.form.post(this.route('reports.store'))
        },
    },
}
</script>
