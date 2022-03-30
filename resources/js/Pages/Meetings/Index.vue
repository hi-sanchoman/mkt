<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Встречи</h1>
    <div class="container">
    <div class="operations">
        <div class="operations__blocks">
            <a class="btn operations__resident">Вернуться</a>
        </div>
        <div class="operations__blocks">
            <a class="btn operations__flat filter">Добавить клиента</a>
        </div>
    </div>


    <div class="client__table">
        

        <table class="iksweb">
            <tbody>
                <tr class="table_header">
                    <td>Клиент</td>
                    <td>Тэги</td>
                    <td>Создан</td>
                    <td>Email</td>
                    <td>Телефон</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag new">новый</div><div class="tag pot">потенциальный</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag buy">купил</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag pot">потенциальный</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag new">новый</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag quit">ушедший</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag new">новый</div><div class="tag pot">потенциальный</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag buy">купил</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag new">новый</div><div class="tag pot">потенциальный</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
                <tr>
                    <td class="client_name">Максат Айбергенов</td>
                    <td><div class="tag pot">потенциальный</div></td>
                    <td>25.12.2019</td>
                    <td>maksar_02@gmail.com</td>
                    <td>87059965542</td>
                    <td>|</td>
                </tr>
            </tbody>
        </table>
    
        

    </div>




</div>


  </div>
</template>

<script>
import Layout from '@/Shared/Layout'

export default {
  metaInfo: { title: 'Встречи' },
  layout: Layout,
}
</script>
