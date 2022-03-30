<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Квартирный лист</h1>
    <div class="flats mb-12 flex">
    	<inertia-link class="btn-indigo my_buttons" :href="route('list')">
	        <span>Квартирный</span>
	        <span class="hidden md:inline">лист</span>
    	</inertia-link>
    	<inertia-link class="btn-indigo my_buttons" :href="route('list')">
	        
	        <span class="hidden md:inline">Договор</span>
    	</inertia-link>
    </div>
    <div class="flex">
	    <div class="flats mb-9 flex">
	    	<div class="flat_panel">
	    		<div class="flat_image">
	    			<img src="img/plan.jpg" />
	    		</div>
	    		<div class="flex flat_description">
	    			<div class="d-flex flat_icons"></div>
	    			<div class="flat_information flex justify-between" >
	    				<div class="flat_number mb-6">RP-2B3-9<br><p class="jk_name">Royal Park</p></div>
	    				<div class="flat_price">$ 150'000</div>
	    							
	    			</div>
	    		

	    		</div>
	    		<hr>
	    		<br>
	    		<div class="flex justify-between">
	    			<div class="flat_options d-flex">
    					<div>Спальни</div>
    					<br>
    					<div>2</div>
    					<br>
    					<div>Этаж</div>
    					<br>
    					<div>2</div>
    					<br>
	    			</div> 
	    			<div class="flat_options d-flex">
    					<div>Ванная</div>
    					<br>
    					<div>1</div>
    					<br>
    					<div>Лоджия</div>
    					<br>
    					<div>1</div>
	    			</div>
    				<div class="flat_options d-flex">
    					<div>Блок</div>
    					<br>
    					<div>В</div>
    					<br>
    					<div>Подъезд</div>
    					<br>
    					<div>2</div>
    				</div>
    				<div class="flat_options d-flex">
    					<div>Площадь</div>
    					<br>
    					<div>115 м<sub>2</sub></div>
    				</div>
	    		</div>
	    		<br>
    			<div class="opisanie flex">
    				<div>Описание</div>
    				<div class="flat_text">is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </div>
    			</div>
	    	</div>
	    	<div class="variants_panel"></div>
	    </div>
	    <div class="flats mb-3 d-flex">
	    	<div class="other_images d-flex">
	    		<div class="image_column flex">
		    		<img class="flat_image" src="img/flats/1.webp" />
		    		<img class="flat_image" src="img/flats/2.webp" />
	    		</div>
	    		<div class="image_column flex">
		    		<img class="flat_image" src="img/flats/3.webp" />
		    		<img class="flat_image" src="img/flats/4.webp" />
	    		</div>
	    	</div>
	    	<div class="customer_attribute">КОМПЛЕКТАЦИЯ КВАРТИРЫ</div>
	    	<ul>
	    		<li>Чистая отделка</li>
	    		<br><br>
	    		<li>Обои под покраску, пропитанные антисептиком</li>
	    		<br><br>
	    		<li>Кондиционер в каждой комнате</li>
	    		<br><br>
	    		<li>Электронагреватель воды</li>
	    		<br><br>
	    		<li>Разводка под интернет, спутниковое телевидение</li>
	    		<br><br>
	    		<li>Зонированное освещение</li>
	    		<br><br>
	    		<li>Деревянные межкомнатные двери</li>
	    		<br><br>
	    	</ul>
	    </div>
	    <div class="flats mb-3 d-flex">
	    	<div class="customer">Информация о покупателе:</div>

	    	<p class="customer_name">Аскар Муратов</p>
	    	<div>Экономист</div>
	    	<br>
	    	<hr>
	    	<div class="customer_attribute">Email</div>
	    	<div>askar.muratov@mail.ru</div>
	    	<div class="customer_attribute">Телефон</div>
	    	<div>+7 778 777 77 99</div>
	    	<div class="customer_attribute">ИИН</div>
	    	<div>750112156908</div>
	    	<div class="customer_attribute">История</div>
	    	<div>Показ квартиры</div>
	    	<br>
	    	<div>Обсуждение цены</div>
	    	<br>
	    	<div>Не устроила цена</div>
	    	<br>
	    	<div>Договор подписан</div>

	    </div>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'

export default {
  metaInfo: { title: 'List' },
  layout: Layout,
}
</script>
