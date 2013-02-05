<table style="width: 100%">
  <tr>
  {$imgindex=0}
  {foreach $images as $image}
    {$imgindex=$imgindex+1}
    <td style="width: 25%">
      <div class="thumbcontainer">
        <div class="thumb" style="margin: {if ($image.thumbh < $image.thumbw)}35{else}15{/if}px auto">
          <a href="{$image.imgurl}"><img src="{$image.thumburl}" width="{if ($image.thumbh < $image.thumbw)}120px{else}80px{/if}" height="{if ($image.thumbh > $image.thumbw)}120px{else}80px{/if}"/></a>
        </div>
       </div>
    </td>
    {if $imgindex%4==0}</tr><tr>{/if}
  {/foreach}
  </tr>
</table>
