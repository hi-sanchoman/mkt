<template>
  <div class="flex flex-col h-full">
 

    <div class="flex flex-auto">
      <!-- <div class="w-3/12 flex flex-col">
        <h1 class="mb-8 font-bold text-2xl">Календарь</h1>
        <div class="bg-white rounded-2xl shadow-sm w-full h-full overflow-y-auto p-6">
          <div class="mb-4 flex flex-col gap-3">
          <label class="font-medium">
            По ответственности
          </label>
          <select class="w-full px-4 py-2 border border-gray-200 font-normal rounded">
            <option value="">Все задачи</option>
            <option value="">Свои</option>
            <option value="">Порученные</option>
          </select>
        </div>
          <div class="mb-4  flex flex-col gap-3">
            <label  class="font-medium">
              По типу
            </label>
            <select class="w-full px-4 py-2 border border-gray-200 font-normal rounded">
              <option value="">Все задачи</option>
              <option value="">Задачи</option>
              <option value="">Встречи</option>
            </select>
          </div>
          

        <div class="mb-4  flex flex-col gap-3">
          <label  class="font-medium">
            По срочности
          </label>
          <select class="w-full px-4 py-2 border border-gray-200 font-normal rounded">
            <option value="">Несрочные</option>
            <option value="">Срочные</option>
          </select>
        </div>
        </div>
      </div>   -->
      <div class="w-full pl-3">
        <Fullcalendar :options="calendarOptions" />
      </div>  
    </div>
    


    <modal name="more">
      <div class="flex flex-col p-6">
        <div>
       <h1 class="mb-8 font-medium text-xl">
            {{eventObj.title}}
          </h1>
      <div class="flex flex-wrap">
        <div class="w-1/3 mb-3">
              <p class="font-medium text-gray-400 mb-3">Дедлайн</p>
              <p class="font-medium text-black">{{ eventObj.extendedProps.deadline}}</p>
            </div>

             <div class="w-1/3 mb-3">
                <p class="font-medium text-gray-400 mb-3">Ответственный</p>
                <div class="font-medium text-black">
                  {{ eventObj.extendedProps.user.last_name + ' ' + eventObj.extendedProps.user.first_name}}
                </div>
            </div>

             <div class="w-full mb-3">
              <p class="font-medium text-gray-400 mb-3">Описание</p>
              <div class="font-medium text-black">{{ eventObj.extendedProps.description}}</div>
            </div>

      </div>
        </div>
      </div>
    </modal>

      <modal name="create">
      <create-task :type="1" :task_id="0" :date="date"></create-task>
    </modal>

  </div>
</template>



<script>

import Layout from '@/Shared/Layout'
import Fullcalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import ListPlugin from '@fullcalendar/list'
import ruLocale from '@fullcalendar/core/locales/ru';
import createTask from './Create.vue'

export default {
props:{
    event: Array, 
},
data() {
    return {
       date: Date.now(),
       test: "test",
       eventObj: {
         extendedProps: {
           user: {},
           auditor: {},
         }
       },
       calendarOptions: {
          plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin, ListPlugin],
         
          initialView: 'dayGridMonth',
          headerToolbar: {
            left: 'prevYear,prev,next,nextYear today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,listWeek'
          },
          navLinks: true, // can click day/week names to navigate views
          editable: true,
          selectable:true,
          height: window.innerHeight - 100,
          locale:ruLocale,
          dayMaxEvents: true,
          events: this.event,
          select: (event) => {
            // this.handleSelect()
          },
          eventClick: (info) => {
            // console.log(event)
           
                  this.eventObj = info.event;

            this.$modal.show('more')
          },
          dateClick: (info) => {
              // this.date = info;
              this.date = info.date;
              
                this.eventObj = info.event;

                this.$modal.show('create');
    
          },
          windowResize: function(arg) {
            console.log(arg)
            this.calendarOptions.height = window.innerHeight - 100
          },
          // eventMouseEnter: (event) => {
            
          // }
        },
      }
  },
  metaInfo: { title: 'Календарь' },
  layout: Layout,
  components:{

    createTask,
    Fullcalendar
  },
  methods:{

    testing(info){

    },
    handleSelect(){
      alert('test');
    }
  },
  created() {
    //let arr = []
    //console.log(this.event);
    /*this.event.forEach((item, index) => {
         console.log(this.event);
        this.calendarOptions.events.push({
          title: this.event.title,
          start: this.event.start,
          end: this.event.end,
          extendedProps: {
            department: this.event.department
          },
          description: this.event.description
       
        })
    });*/
  }
}
</script> 
