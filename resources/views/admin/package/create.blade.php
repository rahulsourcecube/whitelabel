@extends('admin.layouts.master')

@section('main-content')
    <div class="card">
        <div class="card-body">

            <h4>Complex Example</h4>
            <h4>Add Package</h4>

            <div class="m-t-50" style="">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Title</label>
                            <input type="text" class="form-control" id="name" name="title" placeholder="Title"
                                onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)"
                                maxlength="150">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="campaign"> No Of Campaign</label>
                            <input type="text" class="form-control" id="campaign" placeholder="No Of Campaign"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" placeholder="Description">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="inputype">Type</label>
                            <select id="inputype" class="form-control type">

                                <option value="1">Free Trial</option>
                                <option value="2">Monthly</option>
                                <option value="3">Yearly</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputAddress2" class="day_title">No Of Day</label>
                            <input type="text" class="form-control day_place" id="inputAddress2" placeholder="No Of Day"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="price"> Price</label>
                            <input type="text" class="form-control" id="price" name="price"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10"
                                placeholder="Price">
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group  col-md-6">
                            <label for="file">Image</label>
                            <input type="file" class="form-control" id="file">
                        </div>


                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.type').change(function() {

                type = $(this).val();

                if (type == '1') {
                    $('.day_title').html('No Of Day');
                    $(".day_place").attr("placeholder", "No Of Day").placeholder();
                } else if (type == '2') {
                    $('.day_title').html('No Of Month');
                    $(".day_place").attr("placeholder", "No Of Month").placeholder();

                } else {
                    $('.day_title').html('No Of Year');
                    $(".day_place").attr("placeholder", "No Of Year").placeholder();
                }

            })
        })
    </script>
@endsection
