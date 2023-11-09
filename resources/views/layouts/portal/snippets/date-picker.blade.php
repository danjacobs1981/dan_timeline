<div class="control control--datepicker" data-predate="{{ isset($predate) ? $predate : '' }}">
    <span class="control__label">Event Date &amp; Time</span>
    <div>
        <div class="period year add" data-period="year">
            <em data-popover="Add year" data-popover-position="top">
                <i class="fa-solid fa-circle-plus"></i>
                <span>
                    Year
                </span>
            </em>
            <div>
                <strong>
                    Year
                </strong>
                <input type="text" data-date id="year" value="{{ old('date_year') }}" placeholder="YYYY" autocomplete="off" />
                <span data-popover="Remove year" data-popover-position="bottom">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span>
            </div>
        </div>
        <div class="period month" data-period="month">
            <em data-popover="Add month" data-popover-position="top">
                <i class="fa-solid fa-circle-plus"></i>
                <span>
                    Month
                </span>
            </em>
            <div>
                <strong>
                    Month
                </strong>
                <select id="month" data-date>
                    <option value="1" {{ old('date_month') == '1' ? 'selected' : '' }}>January</option>
                    <option value="2" {{ old('date_month') == '2' ? 'selected' : '' }}>February</option>
                    <option value="3" {{ old('date_month') == '3' ? 'selected' : '' }}>March</option>
                    <option value="4" {{ old('date_month') == '4' ? 'selected' : '' }}>April</option>
                    <option value="5" {{ old('date_month') == '5' ? 'selected' : '' }}>May</option>
                    <option value="6" {{ old('date_month') == '6' ? 'selected' : '' }}>June</option>
                    <option value="7" {{ old('date_month') == '7' ? 'selected' : '' }}>July</option>
                    <option value="8" {{ old('date_month') == '8' ? 'selected' : '' }}>August</option>
                    <option value="9" {{ old('date_month') == '9' ? 'selected' : '' }}>September</option>
                    <option value="10" {{ old('date_month') == '10' ? 'selected' : '' }}>October</option>
                    <option value="11" {{ old('date_month') == '11' ? 'selected' : '' }}>November</option>
                    <option value="12" {{ old('date_month') == '12' ? 'selected' : '' }}>December</option>
                </select>
                <span data-popover="Remove month" data-popover-position="bottom">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span>
            </div>
        </div>
        <div class="period day" data-period="day">
            <em data-popover="Add day" data-popover-position="top">
                <i class="fa-solid fa-circle-plus"></i>
                <span>
                    Day
                </span>
            </em>
            <div>
                <strong>
                    Day
                </strong>
                <select id="day" data-date>
                    <option value="1" {{ old('date_day') == '1' ? 'selected' : '' }}>1st</option>
                    <option value="2" {{ old('date_day') == '2' ? 'selected' : '' }}>2nd</option>
                    <option value="3" {{ old('date_day') == '3' ? 'selected' : '' }}>3rd</option>
                    <option value="4" {{ old('date_day') == '4' ? 'selected' : '' }}>4th</option>
                    <option value="5" {{ old('date_day') == '5' ? 'selected' : '' }}>5th</option>
                    <option value="6" {{ old('date_day') == '6' ? 'selected' : '' }}>6th</option>
                    <option value="7" {{ old('date_day') == '7' ? 'selected' : '' }}>7th</option>
                    <option value="8" {{ old('date_day') == '8' ? 'selected' : '' }}>8th</option>
                    <option value="9" {{ old('date_day') == '9' ? 'selected' : '' }}>9th</option>
                    <option value="10" {{ old('date_day') == '10' ? 'selected' : '' }}>10th</option>
                    <option value="11" {{ old('date_day') == '11' ? 'selected' : '' }}>11th</option>
                    <option value="12" {{ old('date_day') == '12' ? 'selected' : '' }}>12th</option>
                    <option value="13" {{ old('date_day') == '13' ? 'selected' : '' }}>13th</option>
                    <option value="14" {{ old('date_day') == '14' ? 'selected' : '' }}>14th</option>
                    <option value="15" {{ old('date_day') == '15' ? 'selected' : '' }}>15th</option>
                    <option value="16" {{ old('date_day') == '16' ? 'selected' : '' }}>16th</option>
                    <option value="17" {{ old('date_day') == '17' ? 'selected' : '' }}>17th</option>
                    <option value="18" {{ old('date_day') == '18' ? 'selected' : '' }}>18th</option>
                    <option value="19" {{ old('date_day') == '19' ? 'selected' : '' }}>19th</option>
                    <option value="20" {{ old('date_day') == '20' ? 'selected' : '' }}>20th</option>
                    <option value="21" {{ old('date_day') == '21' ? 'selected' : '' }}>21st</option>
                    <option value="22" {{ old('date_day') == '22' ? 'selected' : '' }}>22nd</option>
                    <option value="23" {{ old('date_day') == '23' ? 'selected' : '' }}>23rd</option>
                    <option value="24" {{ old('date_day') == '24' ? 'selected' : '' }}>24th</option>
                    <option value="25" {{ old('date_day') == '25' ? 'selected' : '' }}>25th</option>
                    <option value="26" {{ old('date_day') == '26' ? 'selected' : '' }}>26th</option>
                    <option value="27" {{ old('date_day') == '27' ? 'selected' : '' }}>27th</option>
                    <option value="28" {{ old('date_day') == '28' ? 'selected' : '' }}>28th</option>
                    <option value="29" {{ old('date_day') == '29' ? 'selected' : '' }}>29th</option>
                    <option value="30" {{ old('date_day') == '30' ? 'selected' : '' }}>30th</option>
                    <option value="31" {{ old('date_day') == '31' ? 'selected' : '' }}>31st</option>
                </select>
                <span data-popover="Remove day" data-popover-position="bottom">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span>
            </div>
        </div>
        <div class="period time" data-period="time">
            <em data-popover="Add time" data-popover-position="top">
                <i class="fa-solid fa-circle-plus"></i>
                <span>
                    Time
                </span>
            </em>
            <div>
                <strong>
                    Time
                </strong>
                <div>
                    <select id="time" data-date>
                        <option value="01" {{ old('date_time_hour') == '01' ? 'selected' : '' }}>1</option>
                        <option value="02" {{ old('date_time_hour') == '02' ? 'selected' : '' }}>2</option>
                        <option value="03" {{ old('date_time_hour') == '03' ? 'selected' : '' }}>3</option>
                        <option value="04" {{ old('date_time_hour') == '04' ? 'selected' : '' }}>4</option>
                        <option value="05" {{ old('date_time_hour') == '05' ? 'selected' : '' }}>5</option>
                        <option value="06" {{ old('date_time_hour') == '06' ? 'selected' : '' }}>6</option>
                        <option value="07" {{ old('date_time_hour') == '07' ? 'selected' : '' }}>7</option>
                        <option value="08" {{ old('date_time_hour') == '08' ? 'selected' : '' }}>8</option>
                        <option value="09" {{ old('date_time_hour') == '09' ? 'selected' : '' }}>9</option>
                        <option value="10" {{ old('date_time_hour') == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ old('date_time_hour') == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ old('date_time_hour') == '12' ? 'selected' : '' }}>12</option>
                    </select>
                    :
                    <select id="time_min" data-date>
                        <option value="00" {{ old('date_time_min') == '00' ? 'selected' : '' }}>00</option>
                        <option value="01" {{ old('date_time_min') == '01' ? 'selected' : '' }}>01</option>
                        <option value="02" {{ old('date_time_min') == '02' ? 'selected' : '' }}>02</option>
                        <option value="03" {{ old('date_time_min') == '03' ? 'selected' : '' }}>03</option>
                        <option value="04" {{ old('date_time_min') == '04' ? 'selected' : '' }}>04</option>
                        <option value="05" {{ old('date_time_min') == '05' ? 'selected' : '' }}>05</option>
                        <option value="06" {{ old('date_time_min') == '06' ? 'selected' : '' }}>06</option>
                        <option value="07" {{ old('date_time_min') == '07' ? 'selected' : '' }}>07</option>
                        <option value="08" {{ old('date_time_min') == '08' ? 'selected' : '' }}>08</option>
                        <option value="09" {{ old('date_time_min') == '09' ? 'selected' : '' }}>09</option>
                        <option value="10" {{ old('date_time_min') == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ old('date_time_min') == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ old('date_time_min') == '12' ? 'selected' : '' }}>12</option>
                        <option value="13" {{ old('date_time_min') == '13' ? 'selected' : '' }}>13</option>
                        <option value="14" {{ old('date_time_min') == '14' ? 'selected' : '' }}>14</option>
                        <option value="15" {{ old('date_time_min') == '15' ? 'selected' : '' }}>15</option>
                        <option value="16" {{ old('date_time_min') == '16' ? 'selected' : '' }}>16</option>
                        <option value="17" {{ old('date_time_min') == '17' ? 'selected' : '' }}>17</option>
                        <option value="18" {{ old('date_time_min') == '18' ? 'selected' : '' }}>18</option>
                        <option value="19" {{ old('date_time_min') == '19' ? 'selected' : '' }}>19</option>
                        <option value="20" {{ old('date_time_min') == '20' ? 'selected' : '' }}>20</option>
                        <option value="21" {{ old('date_time_min') == '21' ? 'selected' : '' }}>21</option>
                        <option value="22" {{ old('date_time_min') == '22' ? 'selected' : '' }}>22</option>
                        <option value="23" {{ old('date_time_min') == '23' ? 'selected' : '' }}>23</option>
                        <option value="24" {{ old('date_time_min') == '24' ? 'selected' : '' }}>24</option>
                        <option value="25" {{ old('date_time_min') == '25' ? 'selected' : '' }}>25</option>
                        <option value="26" {{ old('date_time_min') == '26' ? 'selected' : '' }}>26</option>
                        <option value="27" {{ old('date_time_min') == '27' ? 'selected' : '' }}>27</option>
                        <option value="28" {{ old('date_time_min') == '28' ? 'selected' : '' }}>28</option>
                        <option value="29" {{ old('date_time_min') == '29' ? 'selected' : '' }}>29</option>
                        <option value="30" {{ old('date_time_min') == '30' ? 'selected' : '' }}>30</option>
                        <option value="31" {{ old('date_time_min') == '31' ? 'selected' : '' }}>31</option>
                        <option value="32" {{ old('date_time_min') == '32' ? 'selected' : '' }}>32</option>
                        <option value="33" {{ old('date_time_min') == '33' ? 'selected' : '' }}>33</option>
                        <option value="34" {{ old('date_time_min') == '34' ? 'selected' : '' }}>34</option>
                        <option value="35" {{ old('date_time_min') == '35' ? 'selected' : '' }}>35</option>
                        <option value="36" {{ old('date_time_min') == '36' ? 'selected' : '' }}>36</option>
                        <option value="37" {{ old('date_time_min') == '37' ? 'selected' : '' }}>37</option>
                        <option value="38" {{ old('date_time_min') == '38' ? 'selected' : '' }}>38</option>
                        <option value="39" {{ old('date_time_min') == '39' ? 'selected' : '' }}>39</option>
                        <option value="40" {{ old('date_time_min') == '40' ? 'selected' : '' }}>40</option>
                        <option value="41" {{ old('date_time_min') == '41' ? 'selected' : '' }}>41</option>
                        <option value="42" {{ old('date_time_min') == '42' ? 'selected' : '' }}>42</option>
                        <option value="43" {{ old('date_time_min') == '43' ? 'selected' : '' }}>43</option>
                        <option value="44" {{ old('date_time_min') == '44' ? 'selected' : '' }}>44</option>
                        <option value="45" {{ old('date_time_min') == '45' ? 'selected' : '' }}>45</option>
                        <option value="46" {{ old('date_time_min') == '46' ? 'selected' : '' }}>46</option>
                        <option value="47" {{ old('date_time_min') == '47' ? 'selected' : '' }}>47</option>
                        <option value="48" {{ old('date_time_min') == '48' ? 'selected' : '' }}>48</option>
                        <option value="49" {{ old('date_time_min') == '49' ? 'selected' : '' }}>49</option>
                        <option value="50" {{ old('date_time_min') == '50' ? 'selected' : '' }}>50</option>
                        <option value="51" {{ old('date_time_min') == '51' ? 'selected' : '' }}>51</option>
                        <option value="52" {{ old('date_time_min') == '52' ? 'selected' : '' }}>52</option>
                        <option value="53" {{ old('date_time_min') == '53' ? 'selected' : '' }}>53</option>
                        <option value="54" {{ old('date_time_min') == '54' ? 'selected' : '' }}>54</option>
                        <option value="55" {{ old('date_time_min') == '55' ? 'selected' : '' }}>55</option>
                        <option value="56" {{ old('date_time_min') == '56' ? 'selected' : '' }}>56</option>
                        <option value="57" {{ old('date_time_min') == '57' ? 'selected' : '' }}>57</option>
                        <option value="58" {{ old('date_time_min') == '58' ? 'selected' : '' }}>58</option>
                        <option value="59" {{ old('date_time_min') == '59' ? 'selected' : '' }}>59</option>
                    </select>
                    <select id="time_ampm" data-date>
                        <option value="am" {{ old('date_time_ampm') == 'am' ? 'selected' : '' }}>am</option>
                        <option value="pm" {{ old('date_time_ampm') == 'pm' ? 'selected' : '' }}>pm</option>
                    </select>
                </div>
                <span data-popover="Remove time" data-popover-position="bottom">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span>
            </div>
        </div>
    </div>
    <p>Adding a date is optional. Complete as much (or as little) of the date as required.</p>
</div>

<div class="date-fields">
    <input type="hidden" name="date_year" />
    <input type="hidden" name="date_month" />
    <input type="hidden" name="date_day" />
    <input type="hidden" name="date_time" />
    <input type="hidden" name="date_time_ampm" />
</div>