<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('city') }}'><i class='nav-icon la la-city'></i> {{ trans_choice('admin.cities', 2) }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('place') }}'><i class='nav-icon la la-store-alt'></i> {{ trans_choice('admin.places', 2) }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i class='nav-icon la la-school'></i> {{ trans_choice('admin.menus', 2) }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('category') }}'><i class='nav-icon la la-list-alt'></i> {{ trans_choice('admin.categories', 2) }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('subcategory') }}'><i class='nav-icon la la-list'></i> {{ trans_choice('admin.subcategories', 2) }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('banner') }}'><i class='nav-icon la la-image'></i> {{ trans_choice('admin.banners', 2) }}</a></li>
