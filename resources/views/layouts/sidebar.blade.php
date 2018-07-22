	<aside class="main-sidebar">
		<section class="sidebar">
			<ul class="sidebar-menu" data-widget="tree">
				@switch($login_type)
				@case('Admin')
				<li class="active treeview menu-open">
					<a href="#">
						<i class="fa fa-calendar"></i> <span>打卡記錄</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu" style="">
						@foreach($all_user as $key => $item)
						<li class="
						@if(Request::segment(3) === $item['member_no'])
						active
						@endif
						">
						<a href="{{ url('admin/punch_record/'.$item['member_no']) }}">
							<i class="fa fa-circle-o"></i> 
							{{$item['member_no'].' '.$item['name']}}
						</a>
					</li>
					@endforeach

					<li>
						<a href="{{ url('admin/punch_record_download') }}">
							<i class="fa fa-calendar"></i> <span>下載打卡記錄</span>
						</a>
					</li>

				</ul>
			</li>

			@break
			@default
			<li>
				<a href="{{ url('admin/punch_record') }}">
					<i class="fa fa-calendar"></i> <span>打卡記錄</span>
					<span class="pull-right-container">
						<small class="label pull-right bg-red">{{$forget_punch_num}}</small>
					</span>
				</a>
			</li>
			<li>
				<a href="{{ url('admin/absence') }}">
					<i class="fa  fa-plane"></i> <span>假單申請</span>
				</a>
			</li>
			@endswitch
		</ul>
	</section>
</aside>
