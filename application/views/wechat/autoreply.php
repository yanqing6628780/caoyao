<div class="warp container" ng-controller="ordersCtrl" >
	<div class="row row-offcanvas row-offcanvas-right">
		<div class="col-xs-12 col-sm-9">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">门店</label>
					<div class="col-sm-10">
						<select class="form-control">
							<option ng:repeat="item in usersList.content" value="{{item.id}}">{{item.name}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label" requ>姓名</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">电话</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">订台数目</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">下单</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>