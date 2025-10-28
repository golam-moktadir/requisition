<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Hello</title>
        <style>
            body {font-family: 'solaimanlipi', sans-serif;}
            @page {
                header: page-header;
                footer: page-footer;
            }   
            .font_color{
                color: red;
            }             
        </style>
    </head>
    <body>        
        <!-- 
        <htmlpageheader name="firstpage" style="display:none">
            <div style="text-align:center"><img src="'.'logo.png"/></div>
        </htmlpageheader>
        <sethtmlpageheader name="firstpage" value="on" show-this-page="1" />
        <htmlpageheader name="otherpages" style="display:none">
            <div style="text-align:center">{PAGENO}</div>
        </htmlpageheader>
        <sethtmlpageheader name="otherpages" value="off" /> -->            
        <htmlpageheader name="page-header">
            Your Header Content 
            <span class="font_color">Hello</span>
        </htmlpageheader>
        <htmlpagefooter name="page-footer">
            Your Footer Content {PAGENO}
        </htmlpagefooter>            
        <div>
            <h1 class="font_color">Hello World</h1>

            <table>
                <tr><td>{{ $foo }}</td></tr>
            </table>
            
            <html-separator/>

            <table>
                <tr><td>{{ $bar }}</td></tr>
            </table>

            <html-separator/>

            <h1>
                টেস্টিং pdf কন্টেন্ট
                ০১ 
            </h1>
            
            <html-separator/>
        </div>

    </body>
</html>