<template>
<div>
    <portal-target name="dropdown" slim />
    <div class="md:flex md:flex-col">      

        <div class="md:h-screen md:flex md:flex-col">
            <div class="menu_bar md:flex  px-3 md:px-10 menu_bar ">
                <div class=" md:flex-shrink-0 pr-6 py-2 flex items-center justify-between md:justify-center sm:flex items-center justify-between">
                    <inertia-link class="mt-1" href="/">
                        <img class="w-32" src="/img/new_logo.png">
                    </inertia-link>
                    <button class="mobile-menu-button sm:hidden" @click="showMenu()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="stroke:white" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                </button>
                </div>
                
                    
                <div class="mobile-menu hidden">
                     <div class="ml-8 mt-1">

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('dashboard')">    
                                <div :class="isUrl('') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Поставка молока</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 4 && $page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('conversions')">    
                                <div :class="isUrl('conversions') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Выроботка</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('store')">    
                                <div :class="isUrl('store') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'" >Склад</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('sales')">    
                                <div :class="isUrl('sales') ? 'text-white font-bold underline'  : 'text-white group-hover:text-white font-normal'">Реализация</div>
                            </inertia-link>
                        </div>

                    

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2  && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3 " :href="route('profit')">    
                                <div :class="isUrl('profit') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Зарплата/Расход/Долги</div>
                            </inertia-link>
                        </div>


                        <!--<div class="ml-8">
                            <inertia-link class="flex items-center group py-3" :href="route('zarplata')">    
                                <div :class="isUrl('zarplata') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Зарплата</div>
                            </inertia-link>
                        </div>-->

                        <!--<div class="ml-8">
                            <inertia-link class="flex items-center group py-3" :href="route('dolgi')">    
                                <div :class="isUrl('dolgi') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Долги</div>
                            </inertia-link>
                        </div>-->

                        <div class="ml-8" v-if="$page.props.auth.user.position_id == 1">
                            <inertia-link class="flex items-center group py-3 " :href="route('workers')">    
                                <div :class="isUrl('workers') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Сотрудники</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 4  && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('supp')">    
                                <div :class="isUrl('supp') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Поставщики</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 4 && $page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 2">
                            <inertia-link class="flex items-center group py-3" :href="route('realizators')">    
                                <div :class="isUrl('realizators') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Заявки</div>
                            </inertia-link>
                        </div>
                        
                    </div>
                </div>

                <div class="w-full  md:py-0 md:px-0 text-sm md:text-md flex justify-start items-center hidden sm:block">
                    <div class="mt-1">
                        <!-- <div class="relative">
                            <input class="bg-indigo-500 w-64 rounded-full pl-3 pr-10 py-1 text-white text-sm leading-5" /> 
                            <div class="absolute inset-y-0 right-2 top-1 text-white">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="-4 -2 24 24" class="w-4 h-4">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div> -->
                    </div>
                    <div class="ml-8 mt-1 flex justify-start">

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('dashboard')">    
                                <div :class="isUrl('') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Поставка молока</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 4 && $page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('conversions')">    
                                <div :class="isUrl('conversions') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Выроботка</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('store')">    
                                <div :class="isUrl('store') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'" >Склад</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('sales')">    
                                <div :class="isUrl('sales') ? 'text-white font-bold underline'  : 'text-white group-hover:text-white font-normal'">Реализация</div>
                            </inertia-link>
                        </div>

                    

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2  && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3 " :href="route('profit')">    
                                <div :class="isUrl('profit') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Зарплата/Расход/Долги</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id == 1">
                            <inertia-link class="flex items-center group py-3 " :href="route('workers')">    
                                <div :class="isUrl('workers') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Сотрудники</div>
                            </inertia-link>
                        </div>


                        <!--<div class="ml-8">
                            <inertia-link class="flex items-center group py-3" :href="route('zarplata')">    
                                <div :class="isUrl('zarplata') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Зарплата</div>
                            </inertia-link>
                        </div>-->

                        <!--<div class="ml-8">
                            <inertia-link class="flex items-center group py-3" :href="route('dolgi')">    
                                <div :class="isUrl('dolgi') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Долги</div>
                            </inertia-link>
                        </div>-->

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 4  && $page.props.auth.user.position_id != 3">
                            <inertia-link class="flex items-center group py-3" :href="route('supp')">    
                                <div :class="isUrl('supp') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Поставщики</div>
                            </inertia-link>
                        </div>

                        <div class="ml-8" v-if="$page.props.auth.user.position_id != 4 && $page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 2">
                            <inertia-link class="flex items-center group py-3" :href="route('realizators')">    
                                <div :class="isUrl('realizators') ? 'text-white font-bold underline' : 'text-white group-hover:text-white font-normal'">Заявки</div>
                            </inertia-link>
                        </div>
                        
                    </div>


                </div>

                

                <dropdown class="mt-1 w-48 hidden sm:block" placement="bottom-end">
                    <div class="text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600  whitespace-nowrap">

                        <img :src="'/' + $page.props.auth.user.photo_path" class="h-10 w-10 rounded-full" />

                    </div>
                    <div slot="dropdown" class="mt-2 py-2 shadow-xl bg-white rounded text-sm">
                        <div class="block px-6 py-2 hover:bg-indigo-500 hover:text-white" @click="showProfile">Профиль</div>
                        <inertia-link v-if="$page.props.auth.user.owner" class="block px-6 py-2 hover:bg-indigo-500 hover:text-white" :href="route('users')">Пользователи</inertia-link>
                        <inertia-link class="block px-6 py-2 hover:bg-indigo-500 hover:text-white w-full text-left" :href="route('logout')" method="post" as="button">Выйти</inertia-link>
                    </div>
                </dropdown>
            </div>
            <div class="md:flex md:flex-grow md:overflow-hidden " >

                <div class="md:flex-1 px-4 pt-6 pb-4 md:px-10 md:overflow-y-auto bg-cover bg-gray-100" scroll-region>
                    <!-- style="background: #f9fafb" -->
                    <slot />
                </div> 

            </div>

            <notifications group="foo" position="bottom right" />

            <div class="sidebar fixed  w-screen h-screen overflow-hidden flex  bg-indigo-500 bg-opacity-40 left-0"
                :class="[$page.props.auth.sidebar ? 'left-0' : 'left-full']"
                v-if="$page.props.auth.sidebar" >
                <div class="w-1/5 cursor-pointer" @click="$page.props.auth.sidebar = false"></div>
                <o-chat class="w-4/5 bg-white duration-300  relative" :class="[$page.props.auth.sidebar ? 'left-0' : 'left-full']"/>
            </div>

            <div class="sidebar fixed  w-screen h-screen overflow-hidden flex  bg-indigo-500 bg-opacity-40 left-0"
                :class="[$page.props.auth.sidebar_profile ? 'left-0' : 'left-full']"
                v-if="$page.props.auth.sidebar_profile" >
                <div class="w-3/5 cursor-pointer" @click="$page.props.auth.sidebar_profile = false"></div>
                
                <profile-page class="w-2/5 bg-white  relative" :class="[$page.props.auth.sidebar_profile ? 'left-0' : 'left-full']"></profile-page>    
 
            </div>

            

            <div class="fixed w-screen h-screen flex  bg-indigo-500 bg-opacity-40 left-0 top-0 custom3" :class="[mysidebar ? 'left-0' : 'left-full']"
                v-if="mysidebar">
               
               <div class="w-4/5 bg-white">
                   <div class="h-1/5 w-full bg-indigo-500 place-items-center">
                       <img :src="'/' + $page.props.logo" class="h-full" />
                   </div>
                   <div class="h-4/5 overflow-y-auto">
                       <nav>
                           <a href="/users/profile" class="block px-4 py-2 m-3 shadow flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg><p class="ml-8 mt-1 font-bold">Профиль</p></a>
                           



       <div class="relative block px-4 py-2 m-3 shadow flex" v-if="$page.props.auth.user.position_id == 3" @click="zayavki = !zayavki">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
</svg><p class="ml-8 mt-1 font-bold">Заявки</p>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
</svg>
</div>

    
    <div class="ml-8">
        <!-- <div class="shadow m-3 p-3"><a href="/realizators/new-order">-&nbsp;Новая заявка</a></div> -->
        <div class="shadow m-3 p-3"><a href="/realizators/add-order">-&nbsp;Дополнить</a></div>
        <div class="shadow m-3 p-3"><a href="/realizators">-&nbsp;Текущая заявка</a></div>
        <div class="shadow m-3 p-3"><a :href="'/realizators/istoriya'">-&nbsp;История</a></div>
        <div class="shadow m-3 p-3"><a :href="'/realizators/avans'">-&nbsp;Авансовый отчет</a></div>
        <div class="shadow m-3 p-3"><a :href="'/realizators/nakladnie'">-&nbsp;Накладные</a></div>
    </div>
   
    <div class="relative block px-4 py-2 m-3 shadow flex" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3" @click="postavki = !postavki">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
        </svg>
        <p class="ml-8 mt-1 font-bold">Поставка молока</p>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </div>
    <div v-if="postavki" class="ml-8">
        <div class="shadow m-3 p-3"><a href="/new">-&nbsp;Новая поставка</a></div>
        <div class="shadow m-3 p-3"><a href="/">-&nbsp;История</a></div>
        
    </div>
<div class="relative block px-4 py-2 m-3 shadow flex" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3" @click="vyrabotka = !vyrabotka">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
</svg>
<p class="ml-8 mt-1 font-bold">Выработка</p>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
</svg>
</div>

<div v-if="vyrabotka" class="ml-8">
    <div class="shadow m-3 p-3"><a href="/conversions/new">-&nbsp;Новая переработка</a></div>
    <div class="shadow m-3 p-3"><a href="/conversions">-&nbsp;Помесячный отчет</a></div>
    <div class="shadow m-3 p-3"><a :href="'/conversions/'+$page.props.report">-&nbsp;Скачать отчет</a></div>
    
</div>
           
<div class="relative block px-4 py-2 m-3 shadow flex" @click="sklad = !sklad" v-if="$page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
</svg>
<p class="ml-8 mt-1 font-bold">Склад</p>
   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
</svg>
</div>

<div v-if="sklad" class="ml-8">
    <div class="shadow m-3 p-3"><a href="/store/sklad">-&nbsp;Склад Г.П.</a></div>
    <div class="shadow m-3 p-3"><a href="/store/weight">-&nbsp;Весовой склад</a></div>
    <div class="shadow m-3 p-3"><a href="/store/freezer">-&nbsp;Морозильник</a></div>
    <div class="shadow m-3 p-3"><a href="/store/tara">-&nbsp;Тара/Этикетки</a></div>
    
</div>
        


<div class="relative block px-4 py-2 m-3 shadow flex" @click="realizaciya = !realizaciya" v-if="$page.props.auth.user.position_id != 3">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
</svg>
<p class="ml-8 mt-1 font-bold">Реализация</p>
   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
</svg>
</div>

<div v-if="realizaciya" class="ml-8">
    <div class="shadow m-3 p-3"><a href="/sales/orders">-&nbsp;Заявки</a></div>
    <div class="shadow m-3 p-3"><a href="/sales/realizators">-&nbsp;Реализаторы</a></div>
    <div class="shadow m-3 p-3"><a href="/sales">-&nbsp;Авансовый отчет</a></div>
    <div class="shadow m-3 p-3"><a href="/sales/sold">-&nbsp;Продано</a></div>
</div>
     

<div class="relative block px-4 py-2 m-3 shadow flex" @click="zarplata = !zarplata" v-if="$page.props.auth.user.position_id != 5 && $page.props.auth.user.position_id != 2  && $page.props.auth.user.position_id != 3">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
<p class="ml-8 mt-1 font-bold">Зарплата/Расходы/Долги</p>
   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
</svg>
</div>

<div v-if="zarplata" class="ml-8">
    <div class="shadow m-3 p-3"><a href="/zarplata">-&nbsp;Зарплата</a></div>
    <div class="shadow m-3 p-3"><a href="/dolgi">-&nbsp;Долги</a></div>
    <div class="shadow m-3 p-3"><a href="/uchet">-&nbsp;Учет расходов</a></div>
</div>

<div class="relative block px-4 py-2 m-3 shadow flex">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
</svg>
<p class="ml-8 mt-1 font-bold"><inertia-link class="block px-6 py-2 w-full text-left font-bold" :href="route('logout')" method="post" as="button">Выйти</inertia-link></p>
 
</div>
                       </nav>
                   </div>
               </div>
               <div class="w-1/5 cursor-pointer" @click="mysidebar = false"></div>
            </div>
        </div>
    </div>



</div>

    
</div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Dropdown from '@/Shared/Dropdown'
import OChat from '@/Shared/Chat'
import ProfilePage from '../Pages/Users/Edit'

export default {
    components: {
        Dropdown,
        Icon,
        OChat,
        ProfilePage
    },
    created() {
        
    },
    data() {
        return {
            mysidebar: false,
            zayavki: false,
            postavki: false,
            vyrabotka: false,
            sklad: false,
            realizaciya: false,
            zarplata:false
        }
    },
    watch: {
        '$page.props.flash': {
            handler() {
                if(this.$page.props.flash.success !== null) {
                    this.$notify({
                        group: 'foo',
                        title: 'Успешно',
                        type: 'success',
                        duration: 5000,
                        text: this.$page.props.flash.success
                    });
                }

                if(this.$page.props.flash.error != null) {
                    this.$notify({
                        group: 'foo',
                        title: 'Ошибка',
                        type: 'error',
                        duration: 5000,
                        text: this.$page.props.flash.error
                    });
                }
                
            },
            deep: true,
        },
    },
    methods: {
        showProfile() {
            this.$page.props.auth.sidebar_profile = true
            this.$page.props.actions.selected_user = this.$page.props.auth.user.id
        },
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1)
            if (urls[0] === '') {
                return currentUrl === ''
            }
            return urls.filter(url => currentUrl.startsWith(url)).length
        },
        showMenu(){
            //const btn = this.$el.querySelector(".mobile-menu-button");
            //const menu = this.$el.querySelector(".mobile-menu");
            this.mysidebar = true;
            //menu.classList.toggle("hidden");
        }
    },
} 
</script>
