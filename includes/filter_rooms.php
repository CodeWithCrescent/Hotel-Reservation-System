        <!-- Booking Start -->
        <div class="container-fluid booking pb-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="bg-white shadow" style="padding: 35px;">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <form class="row g-2">
                                <div class="col-md-6">
                                    <div class="date" id="date1" data-target-input="nearest">
                                        <input type="text" id="checkin" name="checkin" class="form-control datetimepicker-input" placeholder="Check in" data-target="#date1" data-toggle="datetimepicker" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="date" id="date2" data-target-input="nearest">
                                        <input type="text" id="checkout" name="checkout" class="form-control datetimepicker-input" placeholder="Check out" data-target="#date2" data-toggle="datetimepicker" />
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                    <select id="adults" name="adults" class="form-select">
                                        <option selected>Adult</option>
                                        <option value="1">Adult 1</option>
                                        <option value="2">Adult 2</option>
                                        <option value="3">Adult 3</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="child" name="child" class="form-select">
                                        <option selected>Child</option>
                                        <option value="1">Child 1</option>
                                        <option value="2">Child 2</option>
                                        <option value="3">Child 3</option>
                                    </select>
                                </div> -->
                            </form>
                        </div>
                        <div class="col-md-2">
                            <button id="search-btn" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Booking End -->