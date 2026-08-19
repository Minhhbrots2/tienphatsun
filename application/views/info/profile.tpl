{* ============================================================
   Trang profile sale cong khai (/profile/<slug>) - clone g-hub.
   Render trong master layout (content-wrapper); toan bo CSS
   landing da duoc scope duoi .ghp de khong dung cham CRM.
   Phase 1: noi dung tinh - phase 2 thay bang bien Smarty.
   ============================================================ *}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,300&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,300&display=swap">
{literal}
<style>

/* ─── BỘ CHỮ ────────────────────────────────────────────────────────────────
   SVN-Optima (bản Việt hoá của Optima) dùng cho TOÀN TRANG: tiêu đề và chữ
   thường cùng một bộ, chỉ khác nét và cỡ.
   `size-adjust:110%`: chiều cao chữ thường của Optima chỉ 0.468em, Inter cũ là
   0.546em ⇒ để nguyên thì cùng một cỡ px chữ nhìn NHỎ HẲN đi. Chỉnh ở
   @font-face để mọi con số font-size đang có trong file giữ nguyên nghĩa, khỏi
   phải dò sửa hàng trăm chỗ. Đặt Y HỆT nhau ở mọi face, lệch một face là chữ
   đậm/nghiêng nhảy cỡ so với chữ thường.
   Nét Black khai dải `800 900` để tiêu đề lớn (khối CTA nét 900) ăn đúng file
   thay vì bị trình duyệt kéo về nét 700. Nét nào không có thì trình duyệt tự
   chọn nét gần nhất (600 → 700), KHÔNG bịa giả. */
@font-face{font-family:'SVN-Optima';font-style:normal;font-weight:400;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-400.woff2') format('woff2')}
@font-face{font-family:'SVN-Optima';font-style:italic;font-weight:400;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-400i.woff2') format('woff2')}
@font-face{font-family:'SVN-Optima';font-style:normal;font-weight:500;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-500.woff2') format('woff2')}
@font-face{font-family:'SVN-Optima';font-style:normal;font-weight:600;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-600.woff2') format('woff2')}
@font-face{font-family:'SVN-Optima';font-style:normal;font-weight:700;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-700.woff2') format('woff2')}
@font-face{font-family:'SVN-Optima';font-style:italic;font-weight:700;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-700i.woff2') format('woff2')}
@font-face{font-family:'SVN-Optima';font-style:normal;font-weight:800 900;font-display:swap;size-adjust:110%;src:url('/landing-assets/personal/fonts/optima-800.woff2') format('woff2')}.ghp{
  --pad:clamp(20px,6.5vw,132px);
  /* Nhỏ đi 20% (180/21vw/320 → 144/16.8vw/256, anh Bình chốt 14/08): ảnh đại
     diện thò 2/3 chiều cao của chính nó xuống dưới ảnh bìa, để to quá là tràn
     qua cả hàng thông tin bên phải. */
  --ava:clamp(144px,16.8vw,256px);
  --ava-left:calc(var(--pad) / 2);
  /* MỘT nguồn sự thật cho chiều cao ảnh bìa — `.cover`, `.ava` và `.coverblock`
     đều neo theo nó (trước đây `.ava` chép cứng lại đúng công thức của
     `.cover`, đổi một chỗ quên chỗ kia là avatar treo lơ lửng giữa ảnh).
     Khung bám TỈ LỆ đúng như ảnh bìa dự án (`aspect-[16/10]` điện thoại →
     `2.7/1` máy tính, xem ProjectDetailLayout.tsx) thay vì chiều cao px cứng:
     ảnh cắt cùng một kiểu ở mọi khổ màn nên chủ trang canh vị trí một lần là
     đúng cả hai. Trần 720px cho màn siêu rộng, không thì màn 2560px ra tấm bìa
     cao 948px nuốt trọn màn hình. */
  --cover-h:min(calc(100vw / 2.7),720px);
  --coral:#ff7a2f; --coral-2:#ff9a5c; --coral-deep:#f2611c;
  --blue:#5b6fe0; --blue-2:#8b9bec; --blue-deep:#4356c9;
  --mint:#25a97c; --violet:#8b7cf0; --gold:#e8912f;
  --slate:#5b6b7c; --ink:#2e3a46; --muted:#8a97a6;
  --line:#eef1f5; --card:#ffffff; --card-2:#f8fafc;
  --tint-co:#ffece1; --tint-bl:#e9ecfb; --tint-mi:#e4f6ee; --tint-go:#fff3e2; --tint-vi:#f0edff;
  --sh-card:0 2px 10px rgba(46,58,70,.05), 0 14px 34px -24px rgba(46,58,70,.3);
  --sh-soft:0 1px 4px rgba(46,58,70,.05);
  --sh-coral:0 8px 18px -8px rgba(255,122,47,.55);
  --radius:18px; --r-md:14px; --r:10px; --r-sm:8px; --pill:99px;
  /* --font = chữ thường (thân bài, mô tả, nút, ô nhập) · --head = tiêu đề.
     Hỏng/chưa tải xong thì rơi về Optima hệ thống (macOS/iOS có sẵn) rồi mới tới
     Inter/system — vẫn cùng dáng chữ nhân văn, không vỡ bố cục. */
  --font:'SVN-Optima',Optima,'Optima Nova',Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  /* Tiêu đề hiện DÙNG CHUNG bộ chữ với thân bài. Giữ biến riêng để sau này muốn
     đổi font tiêu đề thì sửa ĐÚNG một dòng này, không phải rà lại cả file. */
  --head:var(--font);
  --mono:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
}.ghp *{box-sizing:border-box}.ghp{
  margin:0;color:var(--ink);font-family:var(--font);font-size:15px;line-height:1.62;-webkit-font-smoothing:antialiased;
  background:
    radial-gradient(44vmax 32vmax at 100% 0%, rgba(255,122,47,.11), transparent 62%),
    radial-gradient(40vmax 30vmax at 0% 8%, rgba(91,111,224,.07), transparent 60%),
    radial-gradient(46vmax 34vmax at 50% 100%, rgba(255,154,92,.08), transparent 62%),
    linear-gradient(180deg,#ffffff 0%,#fdfefe 52%,#fffaf6 100%) fixed;
  background-color:#fff;
}
/* Tracking tiêu đề nới hơn thời còn Inter (-.012em): Optima có chân nét loe,
   bóp âm mạnh là chữ IN HOA dính nhau (rõ nhất ở "TÔI KHÔNG"). */.ghp h1, .ghp h2, .ghp h3, .ghp h4, .ghp h5, .ghp h6{margin:0;font-family:var(--head);font-weight:700;line-height:1.25;letter-spacing:-.005em}
/* Chữ dạng TIÊU ĐỀ nhưng không nằm trong thẻ h*: nhãn nhỏ trên đầu khối
   (kicker/eyebrow), câu nhấn, tên thẻ, huy hiệu số. Gom một chỗ để sau này thêm
   khối mới chỉ cần nối tên vào đây, đừng rải font-family khắp file. */.ghp .kicker, .ghp .eyebrow, .ghp .grad, .ghp .coverblock .eb, .ghp .leadL .eb, .ghp .ctaband .eb, .ghp .starline, .ghp .step b, .ghp .step .num, .ghp .vdh b, .ghp .cf-pname, .ghp .cf-cap .n, .ghp .cf-cap .r, .ghp .fbrand b, .ghp .fbrand .m, .ghp .ghthanks .bye, .ghp .pledge .hd{font-family:var(--head)}.ghp p{margin:0}.ghp a{color:inherit;text-decoration:none}.ghp button{font:inherit;color:inherit;cursor:pointer;border:none;background:none}.ghp svg{display:block}.ghp figure{margin:0}.ghp :focus-visible{outline:2px solid var(--coral);outline-offset:2px}.ghp .grad{font-weight:800;letter-spacing:-.015em;background:linear-gradient(100deg,var(--coral),var(--coral-deep) 72%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent}

/* ---------- header ---------- */.ghp .head{max-width:none;margin:0;padding:0}.ghp .headcard{position:relative;background:var(--card);border:0;border-radius:0;box-shadow:none}.ghp .cover{
  position:relative;height:var(--cover-h);overflow:visible;
  background-image:url('/uploads/inventory/projects/6a0c9428bd110990941efd31/2026/07/5340093c70695bce-251213_mik_ocean-park_v03_draft-2-1536x864.jpg'),
    linear-gradient(115deg,#ffe6d2 0%,#fdf3ec 34%,#eef2fb 66%,#dfe8fa 100%);
  background-size:cover;background-position:var(--fp-d,center 38%);
}
/* Điểm neo ảnh bìa theo từng khổ màn: ảnh ngang cắt xuống điện thoại rất dễ mất
   mặt người. `--fp-*` do bộ nạp gán từ `cover.focal`; không có thì rơi về khổ
   lớn hơn rồi về mặc định của mẫu. */
@media (max-width:900px){.ghp .cover{background-position:var(--fp-t,var(--fp-d,center 38%))} }
@media (max-width:560px){.ghp .cover{background-position:var(--fp-m,var(--fp-t,var(--fp-d,center 38%)))} }
/* Video bìa nằm DƯỚI lớp veil (đứng trước trong DOM) nên chữ trên bìa vẫn đọc
   được. Không có video thì thẻ này ẩn hẳn, không tải byte nào. */.ghp .cover .covervid{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:var(--fp-d,center 38%);display:none}
/* Video hiện DẦN, không bật cái bụp (anh Bình chốt 17/08 — điện thoại).
   Nền khối bìa đã là ảnh chờ (JS đặt posterImage làm background lúc dựng thẻ)
   nên khách thấy hình NGAY từ khung hình đầu tiên của trang; khi video có đủ
   byte để vẽ thì nó nổi lên đè khít lên chính tấm ảnh đó ⇒ mắt chỉ thấy hình
   tĩnh bỗng chuyển động, không thấy một cú nhảy hay một khoảng trống.
   `opacity` là thứ trình duyệt chạy thẳng trên GPU, không tốn một nhịp vẽ nào. */.ghp .cover.has-vid .covervid{display:block;opacity:0;transition:opacity .55s ease}.ghp .cover.has-vid.vid-on .covervid{opacity:1}
@media (prefers-reduced-motion:reduce){.ghp .cover.has-vid .covervid{transition:none} }
/* Có video bìa mà chủ trang không đặt ảnh riêng thì nền phải là DẢI MÀU, không
   phải tấm ảnh dự án mặc định của mẫu: lúc video còn tải, khách sẽ thấy một
   tấm ảnh chẳng liên quan tới ai. Chủ trang có đặt ảnh thì `data-fbg` ghi
   inline style, thắng luật này. */.ghp .cover.has-vid{background-image:linear-gradient(115deg,#eef2fb 0%,#e6ecfb 40%,#fdf3ec 72%,#ffe6d2 100%)}
@media (max-width:900px){.ghp .cover .covervid{object-position:var(--fp-t,var(--fp-d,center 38%))} }
@media (max-width:560px){.ghp .cover .covervid{object-position:var(--fp-m,var(--fp-t,var(--fp-d,center 38%)))} }.ghp .cover .veil{position:absolute;inset:0;background:linear-gradient(180deg,rgba(20,28,38,.42) 0%,rgba(20,28,38,.18) 42%,rgba(20,28,38,.72) 100%)}.ghp .glass{background:rgba(255,255,255,.78);border:1px solid rgba(255,255,255,.92);-webkit-backdrop-filter:blur(12px);backdrop-filter:blur(12px)}
/* ĐÃ GỠ ô kính "danh hiệu" ở góc phải ảnh bìa (anh Bình chốt 14/08): ảnh bìa để
   trống cho thoáng. Đừng thêm lại — `cover.badge` cũng đã bỏ khỏi dữ liệu. */
/* Khối chữ trên ảnh bìa nằm hẳn ở 1/3 DƯỚI của ảnh (anh Bình chốt 16/08): sau
   khi gỡ ba con số, khối chỉ còn eyebrow + tiêu đề + 2 nút nên đẩy thấp xuống
   mới cân với khoảng trời phía trên. Đừng kéo lên lại giữa ảnh. */.ghp .coverblock{position:absolute;left:50%;bottom:8%;transform:translateX(-50%);z-index:3;text-align:center;width:min(760px,88%)}.ghp .coverblock .eb{font-size:12px;letter-spacing:.28em;text-transform:uppercase;color:rgba(255,255,255,.86);font-weight:700}.ghp .coverblock h2{margin-top:8px;font-size:clamp(20px,3vw,32px);color:#fff;text-shadow:0 4px 22px rgba(0,0,0,.5)}.ghp .coverblock h2 em{font-style:normal;color:#ffc48a}.ghp .coverbtns{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:16px}.ghp .cb{display:inline-flex;align-items:center;gap:8px;height:44px;padding:0 22px;border-radius:var(--pill);font-size:14.6px;font-weight:700;transition:transform .16s ease,filter .16s ease}.ghp .cb:hover{transform:translateY(-1px)}
/* Nút chính dùng CHUNG nền kính với nút phụ (anh Bình chốt 15/08) — chỉ khác ở
   chữ giữ màu cam để mắt vẫn biết đâu là hành động chính. */.ghp .cb.solid{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.62);color:var(--coral-deep);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}.ghp .cb.line{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.62);color:#fff;-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}.ghp .cb svg{width:16px;height:16px}
/* ĐÃ GỠ dải ba con số trên ảnh bìa (dự án đang phân phối · chủ đầu tư · tỉnh
   thành) — anh Bình chốt 16/08, gỡ cả máy tính lẫn điện thoại. Đừng thêm lại:
   `.coverstats`, khoá `hero.stats` trong defaults/shapes và khuôn TPL đều đã bỏ. */.ghp .idrow{display:flex;align-items:flex-end;gap:20px;padding:16px var(--pad) 22px;padding-left:calc(var(--ava-left) + var(--ava) + 28px)}.ghp .ava{position:absolute;left:var(--ava-left);top:calc(var(--cover-h) - var(--ava)/3);z-index:4}.ghp .avatar{
  width:var(--ava);height:var(--ava);border-radius:50%;border:5px solid #fff;overflow:hidden;
  background:linear-gradient(150deg,var(--coral),var(--coral-deep));color:#fff;
  display:grid;place-items:center;font-size:calc(var(--ava) * .34);font-weight:800;letter-spacing:.02em;
  box-shadow:0 20px 44px -18px rgba(255,122,47,.85);
}.ghp .ava .live{position:absolute;right:calc(var(--ava) * .07);bottom:calc(var(--ava) * .05);width:calc(var(--ava) * .13);height:calc(var(--ava) * .13);border-radius:50%;background:var(--mint);border:4px solid #fff;box-shadow:0 0 0 5px rgba(37,169,124,.14)}.ghp .idmain{min-width:0;flex:1;padding-bottom:6px}.ghp .idmain h1{font-size:29px}.ghp .meta{display:flex;flex-direction:column;gap:7px;margin-top:8px;font-size:14px;color:var(--slate)}.ghp .meta i{font-style:normal;display:inline-flex;align-items:center;gap:9px}
/* Icon đầu dòng TRẦN (anh Bình chốt 17/08): bỏ ô nền bo góc, chỉ còn nét icon.
   Bốn dòng dùng CHUNG một bộ nét (outline 1.9, bo đầu, khung 19px) nên đọc ra
   một bộ, phân biệt nhau bằng MÀU: xanh lá đang-nhận-tư-vấn · cam chức danh ·
   xanh dương khu vực · tím địa chỉ web.
   padding:0 là BẮT BUỘC: icon khu vực mang class `b`, trùng tên class nút bấm
   `.b` (padding:0 17px) nên không chặn là nó phình ngang, lệch cả cột. */.ghp .meta .mi{width:19px;height:19px;padding:0;flex:none;display:grid;place-items:center;background:none;color:var(--coral-deep)}.ghp .meta .mi.b{color:var(--blue-deep)}.ghp .meta .mi.g{color:var(--mint)}.ghp .meta .mi svg{width:18px;height:18px}.ghp .meta .on{color:var(--mint);font-weight:700}.ghp .chips{display:flex;flex-wrap:wrap;gap:6px}.ghp .chips:empty{display:none}.ghp .chip{font-size:12.3px;font-weight:700;padding:4px 11px;border-radius:var(--pill);background:var(--card-2);color:var(--slate)}.ghp .chip.ok{background:var(--tint-mi);color:var(--mint)}.ghp .chip.br{background:var(--tint-co);color:var(--coral-deep)}.ghp .chip.wr{background:var(--tint-go);color:var(--gold)}.ghp .chip.bl{background:var(--tint-bl);color:var(--blue-deep)}
/* Chip TRẦN (anh Bình chốt 15/08): chữ mộc không nền — dùng cho dòng chủ đầu
   tư và địa chỉ website ở thẻ giới thiệu. Icon nhỏ đứng trước chữ qua .cic. */.ghp .chip.plain{background:transparent;padding:4px 0}.ghp .chip .cic{display:inline-block;vertical-align:-3px;margin-right:5px}.ghp .chip .cic svg{width:14px;height:14px}
/* Dòng địa chỉ web ở thẻ giới thiệu đứng chung cụm với .meta: cùng cỡ chữ, cùng
   cột icon (19px + 9px), icon tím cho khác ba dòng trên. */.ghp .headcard .chip.plain{display:inline-flex;align-items:center;font-size:14px;font-weight:600;color:var(--slate);padding:0}.ghp .headcard .chip .cic{width:19px;height:19px;display:inline-flex;align-items:center;justify-content:center;flex:none;margin-right:9px;vertical-align:0;color:var(--violet)}.ghp .headcard .chip .cic svg{width:18px;height:18px}

/* nút icon góc phải */.ghp .idacts{display:flex;align-items:center;gap:9px;flex:none;padding-bottom:8px}.ghp .ib{position:relative;width:46px;height:46px;border-radius:50%;display:grid;place-items:center;border:1px solid var(--line);background:#fff;box-shadow:var(--sh-soft);transition:transform .16s ease,box-shadow .16s ease}.ghp .ib svg{width:19px;height:19px}.ghp .ib:hover{transform:translateY(-2px);box-shadow:0 10px 20px -12px rgba(46,58,70,.6)}.ghp .ib.co{background:var(--tint-co);border-color:transparent;color:var(--coral-deep)}.ghp .ib.bl{background:var(--tint-bl);border-color:transparent;color:var(--blue-deep)}.ghp .ib.mi{background:var(--tint-mi);border-color:transparent;color:var(--mint)}.ghp .ib.vi{background:var(--tint-vi);border-color:transparent;color:var(--violet)}.ghp .ib.main{width:52px;height:52px;background:linear-gradient(135deg,var(--coral),var(--coral-deep));border-color:transparent;color:#fff;box-shadow:var(--sh-coral)}.ghp .ib.main svg{width:22px;height:22px}.ghp .ib .tip{position:absolute;top:calc(100% + 9px);left:50%;transform:translateX(-50%) translateY(-4px);background:rgba(46,58,70,.94);color:#fff;font-size:12.2px;font-weight:600;white-space:nowrap;padding:6px 10px;border-radius:8px;opacity:0;pointer-events:none;transition:opacity .16s ease,transform .16s ease;z-index:5}.ghp .ib:hover .tip{opacity:1;transform:translateX(-50%) translateY(0)}.ghp .b{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:40px;padding:0 17px;border-radius:var(--r);font-size:14px;font-weight:700;border:1px solid transparent;transition:filter .16s ease,transform .16s ease,box-shadow .16s ease,border-color .16s ease,color .16s ease}.ghp .b svg{width:16px;height:16px}.ghp .b-primary{color:#fff;background:linear-gradient(135deg,var(--coral),var(--coral-deep));box-shadow:var(--sh-coral)}.ghp .b-primary:hover{filter:brightness(1.06);transform:translateY(-1px)}.ghp .b-soft{background:var(--tint-co);color:var(--coral-deep)}.ghp .b-ghost{background:var(--card);border-color:var(--line);color:var(--ink)}.ghp .b-ghost:hover{border-color:var(--coral);color:var(--coral-deep)}.ghp .b-sm{height:32px;padding:0 12px;font-size:12.8px;border-radius:var(--r-sm)}.ghp .b-lg{height:50px;padding:0 22px;font-size:15px}.ghp .b-block{width:100%}

/* ---------- menu kính ---------- */.ghp .tabs{position:sticky;top:10px;z-index:40;margin-top:14px}
/* Cuộn tới section thì chừa chỗ cho thanh menu dính phía trên, khỏi che tiêu đề. */.ghp #gioithieu, .ghp #video, .ghp #duan, .ghp #nhatky, .ghp #quandiem, .ghp #dangky, .ghp #ketnoi{scroll-margin-top:86px}.ghp .tabsin{max-width:none;margin:0;padding:0 var(--pad)}.ghp .tabshell{display:flex;align-items:center;gap:10px;padding:7px 9px;border-radius:var(--pill);background:rgba(233,236,242,.72);border:1px solid rgba(255,255,255,.8);-webkit-backdrop-filter:blur(18px) saturate(1.3);backdrop-filter:blur(18px) saturate(1.3);box-shadow:0 10px 26px -18px rgba(46,58,70,.5), inset 0 1px 0 rgba(255,255,255,.8)}.ghp .tabbar{flex:1;min-width:0;overflow-x:auto;scrollbar-width:none}.ghp .tabbar::-webkit-scrollbar{display:none}.ghp .pillbar{display:inline-flex;align-items:center;gap:4px}.ghp .tab{display:inline-flex;align-items:center;gap:7px;white-space:nowrap;padding:8px 15px;border-radius:var(--pill);font-size:13.5px;font-weight:600;color:var(--slate);transition:.15s}.ghp .tab svg{width:16px;height:16px}.ghp .tab:hover{color:var(--coral-deep);background:rgba(255,255,255,.6)}.ghp .tab.on{background:linear-gradient(135deg,var(--coral),var(--coral-deep));color:#fff;box-shadow:var(--sh-coral)}

/* ---------- thân ---------- */.ghp .body{max-width:none;margin:0;padding:6px var(--pad) 120px;display:flex;flex-direction:column;gap:26px}.ghp .card{background:transparent;border:0;border-radius:0;box-shadow:none}.ghp .card-h{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:26px 0 10px;border:0;background:transparent}.ghp .card-h .ht{display:flex;align-items:center;gap:11px;min-width:0}.ghp .card-h h2{font-size:18.5px}.ghp .card-h .more{font-size:13px;font-weight:700;color:var(--coral-deep);flex:none}.ghp .hi{width:34px;height:34px;border-radius:11px;flex:none;display:grid;place-items:center;background:var(--tint-bl);color:var(--blue)}.ghp .hi svg{width:17px;height:17px}.ghp .hi.co{background:var(--tint-co);color:var(--coral-deep)}.ghp .hi.mi{background:var(--tint-mi);color:var(--mint)}.ghp .hi.go{background:var(--tint-go);color:var(--gold)}.ghp .hi.vi{background:var(--tint-vi);color:var(--violet)}.ghp .card-sub{padding:0 0 4px;color:var(--slate);font-size:14.4px;max-width:82ch}

/* giới thiệu */.ghp .introwrap{display:grid;grid-template-columns:1fr 1fr;gap:30px;padding:14px 0 8px}
/* Ô lưới mặc định có min-width:auto = KHÔNG co nhỏ hơn nội dung, nên bảng
   thông tin bên trong đẩy cột rộng ra 523px và tràn ngang cả trang trên điện
   thoại. min-width:0 mới cho 1fr co thật. */.ghp .introwrap > *{min-width:0}
/* `.pledgebox div{display:flex}` bên dưới vơ trúng cả chính khối .pgrid, biến
   nó thành hàng ngang 6 cột chữ dựng đứng trên điện thoại. Chốt lại ở đây:
   .pgrid là khối xếp dọc, từ 720px trở lên mới chia 2 cột. */.ghp .pledgebox .pgrid{display:block}
@media (min-width:720px){.ghp .pledgebox .pgrid{display:grid;grid-template-columns:1fr 1fr;gap:0 22px} }.ghp .introL .bio{font-size:16.2px;color:var(--slate);max-width:78ch;text-align:justify;text-justify:inter-word;hyphens:none}.ghp .introL .bio2{margin-top:16px;font-size:15px;color:var(--slate);max-width:78ch}.ghp .inforows{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin:18px 0 16px}.ghp .inforow{display:flex;gap:11px;align-items:center;border:1px solid var(--line);border-radius:var(--r-md);padding:12px 14px;background:var(--card-2)}.ghp .inforow .ic{width:34px;height:34px;border-radius:10px;flex:none;display:grid;place-items:center;background:var(--tint-co);color:var(--coral-deep)}.ghp .inforow:nth-child(2) .ic{background:var(--tint-bl);color:var(--blue-deep)}.ghp .inforow:nth-child(3) .ic{background:var(--tint-mi);color:var(--mint)}.ghp .inforow:nth-child(4) .ic{background:var(--tint-vi);color:var(--violet)}.ghp .inforow .ic svg{width:16px;height:16px}.ghp .inforow small{display:block;font-size:12.2px;color:var(--muted)}.ghp .inforow b{font-size:14.4px}.ghp .pledgebox{border-radius:var(--r-md);padding:18px;background:linear-gradient(160deg,var(--tint-co),#fff 78%);border:1px solid #f6e3d3}.ghp .pledgebox h4{font-size:16.4px;line-height:1.35;margin-bottom:14px;color:#b9752f}.ghp .pledgebox div{display:flex;gap:10px;align-items:flex-start;font-size:14.2px;color:var(--slate);padding:8px 0;border-top:1px solid rgba(185,117,47,.14)}.ghp .pledgebox div:first-of-type{border-top:0}.ghp .pledgebox .ck{margin-top:2px;width:22px;height:22px;border-radius:50%;flex:none;display:grid;place-items:center;background:rgba(37,169,124,.14);color:var(--mint)}.ghp .pledgebox .ck svg{width:12px;height:12px}.ghp .pledgebox b{color:var(--ink)}

/* ô soạn */.ghp .composer{display:flex;gap:12px;align-items:center;padding:16px 0}.ghp .composer .av{width:46px;height:46px;border-radius:50%;flex:none;background:linear-gradient(150deg,var(--coral),var(--coral-deep));color:#fff;display:grid;place-items:center;font-weight:800;font-size:16px}.ghp .composer .fake{flex:1;height:44px;border-radius:var(--pill);background:var(--card-2);border:1px solid var(--line);display:flex;align-items:center;padding:0 18px;color:var(--muted);font-size:14.4px}.ghp .composer .fake:hover{border-color:var(--coral)}.ghp .composer-acts{display:flex;gap:10px;border-top:1px solid var(--line);padding:12px 0 4px}.ghp .composer-acts button{flex:1;display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:13px 12px;border-radius:var(--pill);font-size:13.8px;font-weight:700;color:var(--ink);
  background:rgba(233,236,242,.72);border:1px solid rgba(255,255,255,.85);
  -webkit-backdrop-filter:blur(16px) saturate(1.25);backdrop-filter:blur(16px) saturate(1.25);
  box-shadow:0 10px 22px -16px rgba(46,58,70,.55), inset 0 1px 0 rgba(255,255,255,.85);
  transition:transform .16s ease, box-shadow .16s ease, background .16s ease}.ghp .composer-acts button:hover{background:rgba(255,255,255,.9);transform:translateY(-1px);box-shadow:0 14px 26px -16px rgba(46,58,70,.6)}.ghp .composer-acts svg{width:18px;height:18px}.ghp .composer-acts .c1{color:var(--coral)}.ghp .composer-acts .c2{color:var(--mint)}.ghp .composer-acts .c3{color:var(--blue)}

/* video */.ghp .vfilter{display:flex;align-items:center;gap:9px;flex-wrap:wrap;padding:12px 0 0}.ghp .vchips{display:flex;gap:6px;overflow-x:auto;scrollbar-width:none;flex:1;min-width:0;padding-bottom:2px}.ghp .vchips::-webkit-scrollbar{display:none}.ghp .vchip{white-space:nowrap;font-size:12.6px;font-weight:700;padding:6px 13px;border-radius:var(--pill);border:1px solid var(--line);background:#fff;color:var(--slate)}.ghp .vchip[aria-pressed="true"]{background:linear-gradient(135deg,var(--coral),var(--coral-deep));border-color:transparent;color:#fff}.ghp .vsearch{display:flex;align-items:center;border:1px solid var(--line);border-radius:var(--r);background:#fff;overflow:hidden;flex:none}.ghp .vsearch input{border:none;background:transparent;height:34px;padding:0 12px;font:inherit;font-size:13.4px;width:170px;outline:none;color:var(--ink)}.ghp .vsearch span{width:34px;height:34px;display:grid;place-items:center;color:var(--muted)}.ghp .vsearch svg{width:15px;height:15px}.ghp .vgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;padding:16px 0 8px}.ghp .vempty{padding:26px 18px;text-align:center;color:var(--muted);font-size:14px}.ghp .vid{position:relative;aspect-ratio:16/9;display:grid;place-items:center;overflow:hidden;border-radius:var(--r-md);border:1px solid var(--line);background-size:cover;background-position:center}.ghp .vid::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(20,28,38,.06),rgba(20,28,38,.42))}
/* ===== CLIP DỌC (TikTok · YouTube Shorts) — anh Bình chốt 18/08 =====
   Ảnh bìa của clip dọc là ảnh 9:16. Nhét vào khung 16:9 kiểu `cover` như video
   quay ngang thì cắt mất khoảng hai phần ba chiều cao — đúng chỗ có mặt người
   và chữ tiêu đề cháy trên clip. Nên khung giữ nguyên 16:9 (hàng thẻ vẫn thẳng
   một dải), ảnh nằm TRỌN ở giữa, hai bên trống lấp bằng CHÍNH ảnh đó phóng to
   + làm mờ — cùng lối YouTube bày Shorts, nên thẻ TikTok và thẻ Shorts đứng
   cạnh nhau đọc như một. */.ghp .vid.vert{background-color:#0d1219}
/* Thứ tự vẽ (cùng một chồng, đều z-index:auto): ::before → các con → ::after.
   Nên lớp MỜ phải nằm ở ::before, còn ảnh SẮC phải đi cùng ::after — nhét ảnh
   sắc vào nền của chính thẻ là bị ::before phủ kín, thẻ chỉ còn một vũng mờ
   (đã dính đúng lỗi này lần dựng đầu). Gradient nằm DƯỚI ảnh sắc trong cùng
   ::after nên chỉ tối hai dải bên, không phủ lên mặt người trong clip. */.ghp .vid.vert::before{content:"";position:absolute;inset:0;background-image:var(--vimg);background-size:cover;background-position:center;
  filter:blur(24px) saturate(1.2) brightness(.6);transform:scale(1.18)}.ghp .vid.vert::after{background-image:var(--vimg),linear-gradient(180deg,rgba(20,28,38,.06),rgba(20,28,38,.42));
  background-size:contain,cover;background-position:center,center;background-repeat:no-repeat,no-repeat}
/* Nút phát kiểu KÍNH (anh Bình chốt 18/08): đĩa trắng ĐẶC 62px trước đây đục
   một lỗ ngay giữa khung — che đúng chỗ đắt nhất của ảnh/video nổi bật. Nay là
   một lớp kính: nền gần như trong, chỉ làm nhoè phần ảnh NGAY DƯỚI nút để mũi
   tên còn đọc được, viền sáng + gờ trong (inset) tạo mép kính. Mũi tên chuyển
   sang trắng vì trên nền trong suốt màu cam của thương hiệu chìm hẳn vào ảnh;
   drop-shadow giữ nó nổi cả khi khung hình dưới nút sáng trắng. */.ghp .play{position:relative;z-index:2;width:62px;height:62px;border-radius:50%;
  background:rgba(255,255,255,.055);border:1px solid rgba(255,255,255,.29);
  -webkit-backdrop-filter:blur(3px) saturate(1.08);backdrop-filter:blur(3px) saturate(1.08);
  display:grid;place-items:center;
  box-shadow:0 10px 24px -18px rgba(12,18,26,.35), inset 0 1px 0 rgba(255,255,255,.28), 0 0 22px rgba(255,255,255,.11);
  transition:transform .16s ease,background .18s ease,border-color .18s ease,box-shadow .18s ease}.ghp .play:hover{transform:scale(1.07);background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.52);
  box-shadow:0 10px 24px -18px rgba(12,18,26,.35), inset 0 1px 0 rgba(255,255,255,.38), 0 0 30px rgba(255,255,255,.24)}.ghp .play svg{width:22px;height:22px;color:rgba(255,255,255,.86);margin-left:3px;filter:drop-shadow(0 1px 3px rgba(12,18,26,.5))}
/* Vòng TOẢ (anh Bình chốt 18/08): kính đã mờ tới mức gần như trong, nếu để trơn
   thì mắt lướt qua không biết đây là chỗ bấm. Hai vòng sáng nở đều RA MỌI HƯỚNG
   rồi tan — nhịp sau lệch nửa chu kỳ với nhịp trước nên luôn có một vòng đang
   toả. Vòng là ::before/::after nên KHÔNG chiếm chỗ trong lưới của nút (svg vẫn
   là ô duy nhất) và không ăn cú bấm (pointer-events:none). */.ghp .play::before, .ghp .play::after{content:"";position:absolute;inset:-1px;border-radius:50%;
  border:1px solid rgba(255,255,255,.42);
  box-shadow:0 0 16px rgba(255,255,255,.19);
  pointer-events:none;opacity:0;animation:ghPlayHalo 3.2s cubic-bezier(.22,.61,.36,1) infinite}.ghp .play::after{animation-delay:1.6s}
@keyframes ghPlayHalo{
  0%{transform:scale(1);opacity:.43}
  55%{opacity:.17}
  100%{transform:scale(1.85);opacity:0}
}
/* Máy không có backdrop-filter (webview cũ) thì nút sẽ TÀNG HÌNH vì nền chỉ
   .055 — đổ lại nền trắng đục vừa phải, vẫn nhẹ hơn đĩa đặc cũ. */
@supports not ((-webkit-backdrop-filter:blur(4px)) or (backdrop-filter:blur(4px))){.ghp .play{background:rgba(255,255,255,.24)}.ghp .play:hover{background:rgba(255,255,255,.36)}
}.ghp .play.sm{width:46px;height:46px}.ghp .play.sm svg{width:16px;height:16px}.ghp .vid .dur{position:absolute;right:9px;bottom:9px;z-index:2;font-family:var(--mono);font-size:11.5px;background:rgba(20,28,38,.86);color:#fff;padding:3px 7px;border-radius:6px}.ghp .vid .tg{position:absolute;left:9px;top:9px;z-index:2;font-size:11.4px;font-weight:700;color:#fff;padding:4px 9px;border-radius:6px;background:linear-gradient(135deg,var(--coral),var(--coral-deep))}.ghp .vidmain{padding:10px 0 0}.ghp .vidcap{padding:12px 0 4px;font-size:14.6px;color:var(--ink);font-weight:600}.ghp .vidcap span{display:block;font-weight:400;font-size:13.2px;color:var(--muted);margin-top:2px}

/* ===== VIDEO NỔI BẬT — CHIA ĐÔI (anh Bình chốt 17/08) =====
   Trước đây ảnh video nổi bật kéo hết bề ngang rồi mới tới dòng chú thích ⇒ một
   tấm ảnh cao gần nửa màn hình, chữ bị đẩy xuống dưới, mắt phải đi hai nhịp.
   Nay: ẢNH bên trái, CHỮ bên phải, hai cột bằng nhau và căn giữa theo chiều dọc
   nên hai bên luôn cân — chú thích dài ngắn thế nào thì khối vẫn không lệch.

   `.vsplit` do JS gắn (xem syncHeroSide): KHÔNG có chữ và KHÔNG gắn dự án thì
   giữ nguyên lối cũ một cột — nửa khung trống bên phải nhìn còn tệ hơn ảnh rộng.
   Chế độ sửa thì luôn chia đôi để chủ trang thấy chỗ gõ chú thích. */.ghp .vsplit{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:clamp(20px,3vw,44px);align-items:center}.ghp .vheroside{min-width:0;display:flex;flex-direction:column;align-items:flex-start;gap:15px}
/* Chip dự án của video nổi bật — cùng lối chấm tròn với thẻ dự án ở chồng ảnh
   điện thoại (.vdtag) để hai chỗ đọc như một. Trống thì JS ẩn hẳn. */.ghp .vheroproj{display:inline-flex;align-items:center;gap:8px;max-width:100%;
  padding:6px 13px;border-radius:var(--pill);border:1px solid var(--line);background:#fff;
  font-size:12.6px;font-weight:800;color:var(--slate)}
/* `display` của class ĐÈ thuộc tính `hidden` của trình duyệt (UA style yếu
   hơn selector class) ⇒ thiếu dòng này thì trang chưa gắn dự án vẫn hiện một
   cái chấm cam trơ trọi trên cột chữ. */.ghp .vheroproj[hidden]{display:none}.ghp .vheroproj i{flex:none;width:7px;height:7px;border-radius:50%;background:linear-gradient(135deg,var(--coral),var(--coral-deep))}.ghp .vheroproj .nm{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
/* Chú thích nay là phần CHỮ CHÍNH của khối, không còn là dòng phụ dưới ảnh. */
/* Chữ CĂN ĐỀU HAI BÊN và KHÔNG in đậm (anh Bình chốt 18/08) — cùng lối chữ với
   bài đăng dự án. Tên video (nhất là tên mượn từ YouTube) hay dài, in đậm cỡ
   26px thì cột phải thành một mảng đen nặng hơn hẳn ảnh bên trái, mà mép phải
   lởm chởm nên hai cột nhìn không ăn nhau. */.ghp .vsplit .vidcap{padding:0;font-size:clamp(18px,1.5vw,23px);font-weight:500;line-height:1.45;
  text-align:justify;text-justify:inter-word;overflow-wrap:break-word}.ghp .vsplit .vidcap span{font-size:14.8px;font-weight:400;line-height:1.62;margin-top:11px}
/* Chưa có chú thích + đang sửa ⇒ ô rỗng cao 0px, không bấm vào đâu được. Chữ mờ
   này CHỈ là ::before (không phải nội dung thật) nên không bao giờ bị lưu nhầm. */.ghp .gh-edit #vherocap:empty::before{content:"Bấm để viết chú thích cho video nổi bật";color:#9aa3b2;font-weight:600;font-size:15px}
@media (max-width:980px){.ghp .vsplit{grid-template-columns:1fr;gap:16px}.ghp .vsplit .vidcap{font-size:17.5px}
}
/* Tên video trên dải: cùng luật chữ với khối nổi bật — căn đều hai bên, không
   in đậm. Cắt còn 3 dòng để các thẻ đứng cạnh nhau cao bằng nhau (tên YouTube
   dài ngắn rất lệch); tên đầy đủ vẫn có ở tooltip (thuộc tính title, xem
   renderVideos) và ở màn hình phát. */.ghp .vitem b{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;
  font-size:14.6px;font-weight:500;margin-top:11px;line-height:1.5;color:var(--ink);
  text-align:justify;text-justify:inter-word;overflow-wrap:break-word}.ghp .vitem small{font-size:13px;color:var(--muted)}

/* ===== CHỒNG ẢNH VIDEO — chỉ điện thoại (≤720px) =====
   Trên điện thoại lưới 1 cột + ô tìm kiếm + "Xem thêm" biến trang video thành
   một danh sách dài lê thê, cuộn mãi không hết. Thay bằng KHUNG ĐỨNG YÊN +
   CHỒNG ẢNH kéo dọc: kéo ảnh trên cùng lên là nó bay đi, ảnh sau phóng to vào
   chỗ, ảnh vừa bay chui xuống đáy chồng (vòng vô tận, không có điểm kết thúc).
   Máy tính GIỮ NGUYÊN lưới cũ — deck chỉ bật khi `.vdeck.on` và ≤720px.

   Chỉ ẢNH động, KHUNG đứng yên (anh Bình chốt 16/08): chữ tiêu đề · dự án ·
   thời lượng · dòng chân nằm ngoài chồng, trong khung trắng cố định; kéo xong
   thì NỘI DUNG trong khung đổi theo ảnh mới (nhoè nhẹ một nhịp) chứ khung
   không nhúc nhích. Nhờ vậy mắt chỉ phải bám một thứ đang chạy.
   Kèm một cái lợi thật: vùng bắt cử chỉ (touch-action:none) thu lại đúng ô ảnh,
   nên phần khung trắng trên/dưới vẫn cuộn trang được bình thường.

   Ảnh NGANG 16:9 — video quay ngang, thumbnail YouTube cũng 16:9, ảnh dọc 9:16
   phải cắt cụt hai bên khung hình. Khung trắng chạy SÁT MÉP MÁY (full ngang),
   chỉ ô ẢNH bên trong mới bo 4 góc — ảnh lùi vào 14px, THẲNG HÀNG với chữ tiêu
   đề, và bo nhẹ 16px thôi (anh Bình chốt 16/08 sau khi xem bản chạy thật: sát
   mép quá thì trông chật, bo 26px thì tròn quá). */.ghp .vdeck{display:none}
@media (max-width:720px){.ghp .vdeck.on{display:block;margin:14px calc(var(--pad) * -1) 0;
    --vd-w:min(100vw,560px);
    --vd-iw:calc(var(--vd-w) - 28px);              /* ô ảnh: lề 14px, thẳng hàng với chữ */
    --vd-th:min(calc(var(--vd-iw) * 9 / 16),58vh);  /* đúng 16:9, chặn khi xoay ngang */
    --vd-fade:1}
  /* Vuốt ngang đổi dự án làm cả khung trượt ra/vào ⇒ phải cắt theo trục ngang,
     nếu không trang mọc thanh cuộn ngang. `clip` (khác `hidden`) không tạo
     khung cuộn nên bóng đổ trên/dưới vẫn tràn ra được. */.ghp .vdeck.on{overflow-x:clip;overflow-y:visible}
  /* Khung trắng cố định. Đổi dự án mới làm nó động (trượt ngang / trồi lên);
     biến do JS lái, KHÔNG dùng transition. */.ghp .vdframe{position:relative;background:#fff;
    box-shadow:0 0 0 1px rgba(20,28,38,.07),0 10px 22px -14px rgba(20,28,38,.6);
    opacity:var(--vd-fade);
    transform:translate3d(var(--vd-x,0px),var(--vd-rise,0px),0) scale(var(--vd-sc,1));
    transform-origin:50% 20%}
  /* Đầu khung: tên video + chấm dự án + thời lượng. KHÔNG đặt tiêu đề dưới ảnh
     — tiêu đề nằm trên thì ảnh chiếm gần trọn khung, đọc như một tấm hồ sơ. */.ghp .vdh{height:92px;overflow:hidden;padding:12px 14px;opacity:var(--vd-tf,1)}.ghp .vdh b{display:block;font-size:15.6px;font-weight:800;line-height:1.32;letter-spacing:-.01em;color:var(--ink);
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.ghp .vdmeta{display:flex;align-items:center;gap:8px;margin-top:7px;min-width:0}.ghp .vdtag{display:inline-flex;align-items:center;gap:6px;min-width:0;font-size:12.2px;font-weight:700;color:var(--slate);
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.ghp .vdtag i{flex:none;width:6px;height:6px;border-radius:50%;background:linear-gradient(135deg,var(--coral),var(--coral-deep))}.ghp .vddur{flex:none;margin-left:auto;font-family:var(--mono);font-size:11.4px;color:var(--muted)}
  /* Sân khấu chỉ chứa CHỒNG ẢNH. Cao hơn ô ảnh 38px để hai ảnh sau (lùi 22px và
     44px, đã thu nhỏ) còn ló ra một vệt dưới đáy — chính vệt đó làm ra cảm giác
     "chồng". Con số 38 bám theo 44px của ảnh thứ ba.
     Sân khấu CẮT phần tràn: kéo lên là ảnh trượt lẩn vào mép trên rồi mất hút
     chứ không đè lên chữ tiêu đề của khung (khung đứng yên mà bị một tấm ảnh
     lướt ngang qua chữ thì nhìn rất bẩn). Vì cắt nên phải chừa thêm 40px ở đáy
     cho bóng đổ của hai ảnh sau, rồi kéo ngược lại bằng margin âm để dòng chân
     không bị đẩy xuống. */.ghp .vdstage{position:relative;height:calc(var(--vd-th) + 78px);margin-bottom:-40px;overflow:hidden}.ghp .vdcard{position:absolute;top:0;left:14px;right:14px;height:var(--vd-th);
    border-radius:16px;overflow:hidden;display:grid;place-items:center;
    background-color:#eef1f6;background-size:cover;background-position:center;
    box-shadow:0 18px 40px -18px rgba(20,28,38,.7);
    backface-visibility:hidden;
    /* Chỉ ẢNH ĐẦU bắt cử chỉ; ảnh sau trơ để ngón tay không kéo nhầm. */
    pointer-events:none;-webkit-user-select:none;user-select:none;-webkit-tap-highlight-color:transparent}.ghp .vdcard.top{pointer-events:auto;touch-action:none;cursor:grab}
  /* `will-change` CHỈ bật trong lúc chồng đang động (JS gắn/gỡ .vdmoving).
     Để thường trực là ép trình duyệt giữ mãi ba lớp GPU CÓ BLUR — trên iPhone
     đó là chỗ ngốn bộ nhớ nhất, mà lúc đứng yên chẳng được gì. */.ghp .vdeck.vdmoving .vdcard{will-change:transform,filter,opacity}.ghp .vdcard::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent,rgba(0,0,0,.25))}
  /* Clip dọc (TikTok · Shorts) — cùng lối với `.vid.vert` ở lưới máy tính: lớp
     mờ ở ::before, ảnh sắc đi cùng ::after (thứ tự vẽ ::before → con → ::after
     nên nhét ảnh sắc vào nền thẻ là bị lớp mờ phủ mất). */.ghp .vdcard.vert{background-color:#0d1219}.ghp .vdcard.vert::before{content:"";position:absolute;inset:0;background-image:var(--vimg);background-size:cover;background-position:center;
    filter:blur(22px) saturate(1.2) brightness(.6);transform:scale(1.18)}.ghp .vdcard.vert::after{background-image:var(--vimg),linear-gradient(180deg,transparent,rgba(0,0,0,.25));
    background-size:contain,cover;background-position:center,center;background-repeat:no-repeat,no-repeat}
  /* Vân sáng lúc chờ ảnh vẫn phải thắng lớp mờ (nó cũng là ::before). */.ghp .vdcard.vert[data-load="0"]::before{background-image:none;filter:none;transform:none}.ghp .vdcard .pl{position:relative;z-index:2;width:58px;height:58px;border-radius:50%;
    background:rgba(255,255,255,.94);display:grid;place-items:center;
    box-shadow:0 14px 30px -12px rgba(20,28,38,.7)}.ghp .vdcard .pl svg{width:20px;height:20px;color:var(--coral-deep);margin-left:3px}.ghp .vdcard .tg{position:absolute;left:12px;top:12px;z-index:2;font-size:11.2px;font-weight:700;color:#fff;
    padding:4px 9px;border-radius:8px;background:linear-gradient(135deg,var(--coral),var(--coral-deep))}
  /* Vân sáng chạy trong lúc ảnh chưa tải — không dùng vòng xoay chờ. */.ghp .vdcard[data-load="0"]{background-image:none !important}.ghp .vdcard[data-load="0"]::before{content:"";position:absolute;inset:0;z-index:1;
    background:linear-gradient(100deg,#eef1f6 20%,#f7f9fc 40%,#eef1f6 60%);background-size:280% 100%;
    animation:vdshim 1.25s linear infinite}
  @keyframes vdshim{from{background-position:180% 0}to{background-position:-80% 0}}
  /* Cả dòng chân đều bấm được, không riêng chữ. `cursor:pointer` không phải để
     làm đẹp: Safari trên iPhone có lúc nuốt sự kiện click của thẻ không-tương-tác,
     có con trỏ này thì nó mới coi là chỗ bấm được. "Xem video" để hẳn là
     <button> cũng vì lý do đó. */.ghp .vdf{height:44px;display:flex;align-items:center;gap:10px;padding:0 14px;opacity:var(--vd-tf,1);
    cursor:pointer;-webkit-tap-highlight-color:transparent}.ghp .vdf .go{flex:none;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;
    padding:8px 0;font-size:12.6px;font-weight:800;color:var(--coral-deep)}.ghp .vdf .go:active{opacity:.55}.ghp .vdf .go svg{width:12px;height:12px}
  /* Chữ phụ bên phải phải co được, nếu không "Xem video" bị ép xuống hai dòng. */.ghp .vdf .by{flex:0 1 auto;min-width:0;margin-left:auto;text-align:right;
    font-size:11.6px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.ghp .vddots{display:flex;align-items:center;justify-content:center;gap:7px;padding:16px 0 2px}.ghp .vddots i{width:6px;height:6px;border-radius:50%;background:rgba(20,28,38,.16)}.ghp .vddots i.on{background:linear-gradient(135deg,var(--coral),var(--coral-deep));width:7px;height:7px}.ghp .vdhint{text-align:center;font-size:12.2px;color:var(--muted);padding-top:2px}
  /* Bật deck: giấu lưới + vạch tiến độ, ảnh nổi bật và ô tìm kiếm — chỉ còn
     hàng pill dự án ở trên và chồng thẻ ở dưới. GIỮ .vrail (hàng chip nằm
     trong đó) nhưng rút hết padding của lớp dính, không thì thừa ra một
     khoảng trống giữa hàng pill và chồng ảnh. */.ghp #video.deckon .vidmain, .ghp #video.deckon .vidcap, .ghp #video.deckon .vgrid, .ghp #video.deckon .vrail .pinbar, .ghp #video.deckon .vsearch{display:none}.ghp #video.deckon .vrail .pinrail-in{padding-top:0;padding-bottom:0}.ghp #video.deckon .vfilter{padding-top:14px}
  @media (prefers-reduced-motion:reduce){.ghp .vdcard[data-load="0"]::before{animation:none}
  }
}

/* dự án — giao diện coverflow của Sổ tay (#projects) */.ghp .projsec{padding:34px 0 10px;text-align:center;overflow:hidden}.ghp .projsec .kicker{font-size:12px;letter-spacing:.24em;text-transform:uppercase;color:var(--blue-deep);font-weight:800}.ghp .projsec h2{margin-top:14px;font-size:clamp(30px,4.6vw,52px);font-weight:800;letter-spacing:-.004em;line-height:1.12;color:#1f2732}.ghp .projsec .lead{font-size:16px;color:var(--muted);max-width:680px;margin:10px auto 26px}.ghp .cf-wrap{position:relative;margin-top:18px}
/* touch-action:pan-y = cử chỉ NGANG thuộc về dải thẻ, cử chỉ DỌC vẫn cuộn
   trang. Thiếu dòng này thì trên điện thoại trình duyệt nuốt luôn cú vuốt
   ngang và dải dự án đứng im. */.ghp .cf-stage{position:relative;height:576px;perspective:1700px;perspective-origin:50% 50%;touch-action:pan-y}.ghp .cf-stage.dragging .cf-card{transition:none}.ghp .cf-card{position:absolute;top:50%;left:50%;width:374px;height:499px;border-radius:24px;overflow:hidden;
  background:linear-gradient(160deg,#fdfdfe,#e9ebf0);border:1px solid rgba(255,255,255,.7);
  box-shadow:0 26px 60px rgba(27,36,51,.22);cursor:pointer;transform-style:preserve-3d;
  transition:transform .6s cubic-bezier(.25,.8,.3,1), opacity .6s, box-shadow .6s}.ghp .cf-card.active{box-shadow:0 36px 84px rgba(242,68,5,.32)}.ghp .cf-card:not(.active)::after{content:'';position:absolute;inset:0;background:rgba(255,255,255,.32);pointer-events:none;z-index:5}.ghp .cf-media{position:absolute;inset:0;background-size:cover;background-position:center 8%}.ghp .cf-grad{position:absolute;inset:0;background:linear-gradient(180deg,transparent 60%,rgba(240,241,244,.55) 100%)}.ghp .cf-pname{position:absolute;left:18px;right:18px;bottom:18px;z-index:4;color:#fff;font-weight:800;font-size:18px;line-height:1.2;text-shadow:0 2px 12px rgba(0,0,0,.65)}.ghp .cf-nav{display:flex;align-items:center;justify-content:center;gap:22px;margin-top:10px}.ghp .cf-btn{width:52px;height:52px;border-radius:50%;border:2px solid #ececec;background:#fff;color:#1b2433;font-size:21px;cursor:pointer;transition:.25s;display:flex;align-items:center;justify-content:center}.ghp .cf-btn:hover{background:#f24405;color:#fff;border-color:#f24405;transform:scale(1.08)}.ghp .cf-cap{min-width:0}.ghp .cf-cap .n{font-size:23px;font-weight:900;color:#1b2433;white-space:nowrap}.ghp .cf-cap .r{font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#f24405;margin-top:2px}.ghp .cf-dots{display:flex;gap:7px;justify-content:center;margin-top:16px;flex-wrap:wrap}.ghp .cf-dot{width:9px;height:9px;border-radius:50%;background:#d8dadf;cursor:pointer;transition:.25s;border:none;padding:0}.ghp .cf-dot.on{background:#f24405;width:26px;border-radius:6px}
@media (max-width:820px){.ghp .cf-stage{height:430px}.ghp .cf-card{width:260px;height:347px}.ghp .cf-nav{gap:8px}.ghp .cf-btn{width:40px;height:40px;font-size:17px;flex:none}.ghp .cf-cap{flex:1 1 0;min-width:0}.ghp .cf-cap .n{font-size:clamp(13px,4.3vw,21px);white-space:nowrap}
}

/* nhật ký khách hàng — đường hầm ảnh kiểu Galaxy Stars (Sổ tay) */.ghp .starsec{background:transparent;border:0;border-radius:0;box-shadow:none;padding:34px 0 22px;overflow:hidden}.ghp .starhead{text-align:center;padding:0}.ghp .starhead .kicker{font-size:12px;letter-spacing:.24em;text-transform:uppercase;color:var(--blue-deep);font-weight:800}.ghp .starhead h2{margin-top:14px;font-size:clamp(30px,4.6vw,52px);font-weight:800;letter-spacing:-.004em;line-height:1.12;color:#1f2732}.ghp .starhead p{margin-top:10px;color:var(--slate);font-size:15.4px}
/* ---- NHẬT KÝ KHÁCH HÀNG: dải ảnh mở ra theo cuộn (thay đường hầm) ---------
   Thay cho "đường hầm ảnh" cũ (bản CSS 3D + bản three.js, đã gỡ 18/08). Bốn cột
   ảnh nằm nghiêng trong một khung tối; cuộn tới đâu khung nở ra tới đó rồi ma
   trận ảnh dựng thẳng dần lên, mỗi cột trôi một tốc độ khác nhau.
   Cách chạy: `.unf` cao gấp mấy lần màn hình để lấy quãng cuộn, `.unf-pin` dính
   giữa khung nhìn, JS đọc tiến độ cuộn của `.unf` rồi bơm thẳng vào style. Mọi
   giá trị đi qua MỘT lò xo (stiffness 100, damping 20, mass 0.5 — đúng bộ số
   bản gốc) nên chuột cuộn giật cỡ nào ảnh cũng trôi mượt.
   Bề ngang: margin âm phá padding của .body cho khung chạm đúng hai mép màn —
   cùng cách với dải Quan điểm/Quy trình. Margin âm phải đặt trên CHÍNH section
   rồi trả lại bằng padding (nếu chỉ đặt trên .unf thì `overflow-x:clip` của
   section cắt luôn phần tràn, khung không bao giờ ra tới mép).
   CẤM đặt overflow:hidden lên #nhatky — `hidden` biến section thành vùng cuộn
   nên position:sticky bên trong chết cứng (xem chú thích ở .creedsec). */.ghp #nhatky{overflow-x:clip;overflow-y:visible;margin:0 calc(-1 * var(--pad));padding:34px var(--pad) 22px}.ghp .unf{position:relative;height:var(--unf-run,620vh);margin:30px calc(-1 * var(--pad)) 0}.ghp .unf-pin{position:sticky;top:0;height:100vh;display:flex;align-items:center;justify-content:center}
/* Khung kính trắng xám, TRÀN NGANG ngay từ đầu (anh Bình chốt 18/08 — bỏ bản
   nền đen bo góc 90vw nở dần). Chỉ còn chiều cao mở ra theo cuộn. Nền phải là
   màu ĐỤC nhẹ chồng lên lớp mờ: để trong suốt hoàn toàn thì ảnh trôi qua nhìn
   thẳng vào nền trang, mất chất kính. */.ghp .unf-banner{position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;
  width:100%;height:80vh;border:0;
  background:linear-gradient(180deg,rgba(255,255,255,.78),rgba(240,243,246,.7));
  -webkit-backdrop-filter:blur(18px) saturate(140%);backdrop-filter:blur(18px) saturate(140%);
  box-shadow:inset 0 1px 0 rgba(255,255,255,.9);will-change:height;backface-visibility:hidden}.ghp .unf-scene{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
  perspective:1000px;pointer-events:none}
/* Hai lớp phủ mép, màu TRÙNG nền kính (trắng xám) — để màu tối như bản nền đen
   cũ là bốn mép khung xám xịt lại.
   Mép TRÊN và DƯỚI dùng DẢI MÀU chứ không dùng bóng đổ: bóng đổ tắt gấp nên
   nhìn ra một đường cắt, còn dải màu đậm 50% ở sát mép rồi mất hẳn ở 1/5 chiều
   cao — ảnh loang dần vào nền, không còn ranh giới (anh Bình chốt 18/08).
   Mép TRÁI/PHẢI vẫn để bóng đổ, mềm sẵn vì cột ảnh chạy dọc. */.ghp .unf-veil{position:absolute;inset:0;z-index:2;pointer-events:none;
  background:linear-gradient(180deg,
    rgba(255,255,255,.5) 0%,rgba(255,255,255,.28) 8%,rgba(255,255,255,0) 20%,
    rgba(243,246,248,0) 80%,rgba(243,246,248,.28) 92%,rgba(243,246,248,.5) 100%)}.ghp .unf-veil.x{background:none;
  box-shadow:inset 130px 0 150px -60px #f4f6f8,inset -130px 0 150px -60px #f4f6f8}
/* "Khoảnh khắc" + "Đồng hành": MỘT CẶP ở GÓC TRÊN BÊN TRÁI, cùng cỡ chữ, dòng
   dưới thụt vào cho khớp đuôi dòng trên. Đặt trên cao vì lúc mới vào khối, dải
   ảnh nghiêng nên mép trái của nó thấp dần xuống — càng lên cao khoảng trắng
   càng rộng, chữ nằm trên đó mới không đè lên ảnh (anh Bình chốt 18/08).
   z-index 4 = TRÊN cả lớp mờ mép (.unf-veil, 2) lẫn dải phản chiếu (.unf-gloss,
   3). Hai lớp kia chỉ được làm mờ ẢNH; chữ phải giữ nguyên độ nét, không dính
   màng trắng nào (anh Bình chốt 18/08). Chữ nhạt dần là do JS hạ opacity theo
   cuộn, KHÔNG phải do bị lớp khác phủ lên. */.ghp .unf-words{position:absolute;z-index:4;left:clamp(18px,4.5vw,104px);top:clamp(28px,8vh,112px);
  display:flex;flex-direction:column;align-items:flex-start;gap:2px;pointer-events:none}.ghp .unf-word{font-family:'Cormorant Garamond',Georgia,'Times New Roman',serif;font-style:italic;font-weight:300;
  font-size:clamp(32px,5.4vw,88px);line-height:1.06;letter-spacing:.005em;color:#16202c;white-space:nowrap;
  text-shadow:0 1px 24px rgba(255,255,255,.95);transform:translateY(var(--wy,0px));
  will-change:opacity,transform}.ghp .unf-word.r{margin-left:1.25em}
/* Phản chiếu mặt kính ở mép trên và mép dưới (anh Bình chốt 18/08): một dải
   sáng ôm sát mép + một vệt loé chéo, đặt TRÊN dải ảnh nên ảnh trôi qua là thấy
   ánh sáng lướt trên mặt kính. Đây là ánh phản chiếu, KHÔNG phải bản sao lộn
   ngược của ảnh — muốn kiểu gương soi thì phải nhân đôi cả ma trận ảnh, nặng
   gấp đôi khung hình.
   z-index 3 = trên cả .unf-veil (2) lẫn dải ảnh; đủ trong suốt để không đục ảnh. */.ghp .unf-gloss{position:absolute;left:0;right:0;z-index:3;pointer-events:none;overflow:hidden;
  height:clamp(58px,12vh,142px)}.ghp .unf-gloss.t{top:0;border-top:1px solid rgba(255,255,255,.9);
  background:linear-gradient(180deg,rgba(255,255,255,.62) 0%,rgba(255,255,255,.3) 32%,rgba(255,255,255,.08) 66%,rgba(255,255,255,0) 100%)}.ghp .unf-gloss.b{bottom:0;border-bottom:1px solid rgba(255,255,255,.75);
  background:linear-gradient(0deg,rgba(255,255,255,.5) 0%,rgba(255,255,255,.24) 34%,rgba(255,255,255,.06) 68%,rgba(255,255,255,0) 100%)}
/* Vệt loé chéo — thứ làm mắt đọc ra "mặt kính" chứ không phải "ảnh bị mờ mép". */.ghp .unf-gloss::after{content:'';position:absolute;inset:-20% -10%;
  background:linear-gradient(102deg,rgba(255,255,255,0) 18%,rgba(255,255,255,.42) 40%,rgba(255,255,255,.06) 56%,rgba(255,255,255,0) 74%)}.ghp .unf-gloss.b::after{background:linear-gradient(78deg,rgba(255,255,255,0) 26%,rgba(255,255,255,.26) 48%,rgba(255,255,255,0) 70%)}.ghp .unf-mat{display:flex;align-items:center;justify-content:center;gap:16px;width:120vw;height:150vh;
  transform-origin:50% 50%;transform-style:preserve-3d;will-change:transform;backface-visibility:hidden}.ghp .unf-col{display:flex;flex-direction:column;gap:16px;width:22vw;min-width:200px;flex:none;
  pointer-events:auto;will-change:transform}.ghp .unf-card{position:relative;width:100%;height:200px;flex:none;overflow:hidden;background:#e8ecf1;cursor:pointer;
  transition:transform .3s ease;will-change:transform;backface-visibility:hidden}.ghp .unf-card:hover{transform:scale(1.02)}
/* Nền sáng nên ảnh chỉ hạ nhẹ độ đậm (.92) — để .8 như bản nền đen là ảnh bạc phếch. */.ghp .unf-card img{display:block;width:100%;height:100%;object-fit:cover;opacity:.92;transition:opacity .3s ease}.ghp .unf-card:hover img{opacity:1}
@media (min-width:641px){.ghp .unf-card{height:300px} }
@media (min-width:768px){.ghp .unf-card{height:400px}.ghp .unf-mat, .ghp .unf-col{gap:24px} }
/* Không có ảnh nhật ký thì thu khối lại — 460vh trống trơn là khách cuộn mãi
   không tới đâu. */.ghp .unf.empty{display:none}
/* Tôn cài "giảm chuyển động": bỏ hẳn phần cuộn–nghiêng, xếp ảnh thành lưới. */.ghp .unf.flat{height:auto;margin:26px 0 0}.ghp .unf.flat .unf-pin{position:static;height:auto;display:block}.ghp .unf.flat .unf-banner{width:100%;height:auto;border-radius:26px;padding:16px}.ghp .unf.flat .unf-scene{position:static;perspective:none;pointer-events:auto}.ghp .unf.flat .unf-veil, .ghp .unf.flat .unf-words, .ghp .unf.flat .unf-gloss{display:none}.ghp .unf.flat .unf-mat{transform:none;width:100%;height:auto;flex-wrap:wrap;align-items:flex-start}.ghp .unf.flat .unf-col{transform:none;width:calc(25% - 18px);min-width:160px}.ghp .starfoot{display:flex;flex-direction:column;align-items:center;gap:16px;padding:26px 0 0}.ghp .starline{font-size:clamp(18px,2.4vw,26px);font-weight:800;letter-spacing:-.01em;color:var(--ink)}.ghp .starline b{background:linear-gradient(100deg,var(--coral),var(--coral-deep) 70%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent}

/* quan điểm */
/* ---- Dải nền 2 màu SÁNG, tràn full bề ngang (anh Bình chỉnh 15/08) -------
   Bỏ kiểu "khung trong khung": hai khối Quan điểm + Quy trình KHÔNG còn là
   tấm thẻ bo góc nữa mà là DẢI nền sáng chạy hết bề ngang trang (margin âm
   phá padding của .body), hai quầng chàm + cam nhạt giữ chất 2 màu; chỉ còn
   MỘT lớp thẻ trắng nổi lên trên. */
/* `overflow:clip` chứ KHÔNG phải `hidden`: hai khối này chứa dải thẻ chạy
   ngang theo cuộn (.pinrail) mà `hidden` biến khối cha thành vùng cuộn →
   position:sticky bên trong chết cứng. `clip` cắt y hệt nhưng không tạo vùng
   cuộn nên sticky vẫn bám theo khung nhìn. */.ghp .creedsec, .ghp #quytrinh{position:relative;overflow:clip;border:0;border-radius:0;box-shadow:none;
  margin:0 calc(-1 * var(--pad))}
/* Nền nối LIỀN MẠCH giữa hai khối: gradient nền thuần NGANG (90deg) nên mọi
   hàng điểm ảnh theo chiều dọc giống hệt nhau → mép dưới khối trên và mép
   trên khối dưới trùng màu tuyệt đối; quầng chàm/cam đặt XA đường nối (trên
   cùng của Quan điểm, dưới cùng của Quy trình). #quytrinh kéo margin âm đè
   lên khe gap 26px của .body để hai khối chạm nhau. */.ghp .creedsec{padding:56px var(--pad) 50px;text-align:center;
  background:
    radial-gradient(62% 90% at 96% 0%, rgba(255,122,47,.16), transparent 58%),
    radial-gradient(70% 96% at 4% 0%, rgba(67,86,201,.14), transparent 60%),
    linear-gradient(90deg,#edf1fd 0%,#f7f6ff 48%,#fff1e7 100%)}.ghp #quytrinh{margin-top:-26px;
  background:
    radial-gradient(70% 96% at 0% 100%, rgba(67,86,201,.15), transparent 60%),
    radial-gradient(62% 90% at 100% 100%, rgba(255,122,47,.16), transparent 58%),
    linear-gradient(90deg,#edf1fd 0%,#f7f6ff 48%,#fff1e7 100%)}.ghp .creedsec .eyebrow{font-size:12px;letter-spacing:.24em;text-transform:uppercase;color:var(--blue-deep);font-weight:800}.ghp .creedsec h2{font-family:var(--head);font-weight:800;font-style:normal;letter-spacing:-.004em;font-size:clamp(30px,4.6vw,52px);line-height:1.12;margin-top:14px;color:#1f2732}
/* "KHÔNG ĐÁNH ĐỔI" xuống DÒNG RIÊNG, cùng cỡ đậm, phủ gradient chàm→tím→cam
   (theo ảnh mẫu 15/08) — bỏ lối serif nghiêng cũ. */
/* Đầu khối Dự án và Nhật ký dùng CHUNG cách hiển thị này (anh Bình chốt
   16/08): dòng trên chữ đen, vế nhấn xuống DÒNG RIÊNG phủ gradient. */.ghp .creedsec h2 em, .ghp .projsec h2 em, .ghp .starhead h2 em, .ghp #quytrinh .card-h .grad em{display:block;font-family:var(--head);font-style:normal;font-weight:800;letter-spacing:-.004em;
  background:linear-gradient(92deg,#4356c9 0%,#8b7cf0 38%,#ff7a2f 92%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent}
/* ⚠️ MẤT DẤU TIẾNG VIỆT Ở CHỮ TÔ GRADIENT — nhớ kỹ, sửa CSS chỗ này rất dễ tái
   phát: `-webkit-background-clip:text` chỉ tô trong ĐÚNG hộp nền của phần tử,
   mà tiêu đề để line-height 1.12 nên hộp còn thấp hơn chữ IN HOA CÓ DẤU
   (Ồ · Ấ · Ữ) ⇒ dấu bị cắt cụt, đọc thành chữ không dấu. Nới hộp bằng padding
   rồi kéo lại đúng bằng margin âm: chỗ tô cao thêm, bố cục KHÔNG đổi.
   (Font Inter cũ dấu thấp nên không lộ; Optima đặt dấu cao hơn hẳn.) */.ghp .grad, .ghp .starline b, .ghp .creedsec h2 em, .ghp .projsec h2 em, .ghp .starhead h2 em, .ghp #quytrinh .card-h .grad em{
  padding-top:.22em;margin-top:-.22em;padding-bottom:.1em;margin-bottom:-.1em}
/* Hai dòng tiêu đề lớn phải thở hơn: dấu của dòng dưới suýt chạm chân dòng
   trên khi line-height 1.12. */.ghp .creedsec h2, .ghp .projsec h2, .ghp .starhead h2, .ghp #quytrinh .card-h .grad{line-height:1.2}
/* Chim phượng vàng trang trí giữa tiêu đề và câu dẫn (tách nền trong suốt từ
   ảnh mẫu). multiply để hoà vào dải nền sáng. */.ghp .creedbird{display:block;width:min(150px,34vw);margin:10px auto 12px;mix-blend-mode:multiply;pointer-events:none}.ghp .creedsec .intro{max-width:52ch;margin:0 auto;color:var(--slate);font-size:16px;text-wrap:balance}.ghp .creedgrid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:34px}
/* Thẻ quan điểm bố cục CĂN GIỮA: icon tròn viền gradient chàm→cam → tiêu đề
   IN HOA → vạch duo → mô tả ngắn. Viền gradient = 2 lớp background (trắng
   padding-box đè lên gradient border-box) trên border trong suốt. */.ghp .cbox{background:#fff;border:1px solid #edeff8;border-radius:20px;padding:34px 26px 30px;text-align:center;
  display:flex;flex-direction:column;align-items:center;box-shadow:0 16px 36px -28px rgba(35,45,110,.5)}.ghp .cbox .ti{width:92px;height:92px;border-radius:50%;display:grid;place-items:center;color:#e0521a;
  border:2px solid transparent;
  background:linear-gradient(#fff,#fff) padding-box,linear-gradient(150deg,#4356c9,#f2611c) border-box;
  margin-bottom:20px}.ghp .cbox .ti svg{width:36px;height:36px}.ghp .cbox h3{font-size:18px;line-height:1.35;font-weight:800;letter-spacing:.03em;text-transform:uppercase;color:#1f2732}.ghp .cbox h3 b{color:#c8420e;font-weight:800}.ghp .cbox .crule{width:44px;height:3px;border-radius:2px;background:linear-gradient(90deg,#4356c9,#f2611c);margin:12px auto 14px;flex:0 0 auto}.ghp .creedquote{max-width:78ch;margin:34px auto 0;padding-top:26px;border-top:1px solid rgba(67,86,201,.18);font-size:clamp(16px,1.6vw,19px);font-weight:700;color:var(--ink);font-style:italic}
/* Câu quote đã dồn lên làm câu dẫn đầu khối — quote rỗng thì ẩn hẳn (data-fhtml
   không có cơ chế fwrap), user tự thêm quote trong trình sửa thì lại hiện. */.ghp .creedquote:empty{display:none}.ghp .cbox p{font-size:14.8px;line-height:1.7;color:var(--slate);max-width:30ch;margin:0 auto}

/* quy trình · FAQ */
/* ---- Dải thẻ CHẠY NGANG THEO CUỘN (.pinrail) -----------------------------
   Anh Bình chốt 16/08: BỎ lối tự trôi vô cực của hai khối Quan điểm + Quy
   trình. Nay dải thẻ DÍNH lại giữa khung nhìn, cuộn chuột / vuốt bao nhiêu
   thì thẻ đi ngang bấy nhiêu (1px cuộn = 1px ngang); tới thẻ cuối là nhả ra,
   trang cuộn tiếp đúng hướng người xem đang cuộn — giống khối "Năm mảnh ghép".

   Cố tình KHÔNG bắt sự kiện wheel/touch: chỉ đọc vị trí cuộn rồi dịch thẻ.
   Nhờ vậy vuốt trên điện thoại vẫn là cuộn NGUYÊN BẢN của trình duyệt (có đà,
   không kẹt, cuộn ngược lên cũng chạy lùi đúng nhịp) và không cướp cú lăn của
   ai. Chiều cao khối = chiều cao dải + đúng quãng đường ngang, do JS đặt.
   .pinrail full-bleed (margin âm) để thẻ trôi ra tận mép màn hình; padding
   trái giữ thẻ đầu thẳng cột chữ. */.ghp .pinrail{position:relative;margin:0 calc(-1 * var(--pad))}.ghp .pinrail-in{padding:6px var(--pad) 20px}
/* Chỉ khi JS bật pin mới sticky — chế độ sửa và máy xin ít chuyển động vẫn là
   lưới đứng yên, bấm vào từng thẻ để sửa được. */.ghp .pinrail.is-pinned > .pinrail-in{position:sticky;overflow:hidden;padding-right:0}.ghp .pinrail .gpin{display:flex;flex-wrap:nowrap;overflow:visible;will-change:transform}.ghp .pinrail .gpin > *{min-width:0}.ghp .creedgrid.gpin > *{flex:0 0 calc((100% - 40px)/3)}.ghp .steps.gpin > *{flex:0 0 calc((100% - 24px)/3)}
/* Dải video dùng CHUNG cơ chế này (anh Bình chốt 17/08, thay nút "Xem thêm")
   nhưng KHÔNG phải một hàng dài: giữ nguyên bố cục cũ 2 HÀNG × 3 VIDEO, cuộn
   chuột thì CẢ HAI HÀNG trượt ngang cùng lúc.
   ⇒ đè `display:flex` của .pinrail .gpin bằng lưới: số cột do JS đặt vào
   `--vcols` = ceil(số thẻ / 2) (xem renderVideos), dòng chảy vẫn là ROW nên thứ
   tự đọc giữ nguyên trái→phải: hàng trên là nửa đầu danh sách, hàng dưới nửa
   sau — y như lưới cũ. Cột rộng đúng bằng ô lưới cũ (100% khung ÷ 3, gap 22px)
   nên lúc đứng yên và lúc chạy ngang trông vẫn là một khối.
   Thẻ căn ĐỈNH: tiêu đề video dài ngắn khác nhau, để hàng kéo giãn bằng nhau
   thì ảnh bị đẩy lệch mỗi thẻ một kiểu. */.ghp .pinrail .vgrid.gpin{display:grid;grid-auto-flow:row;align-items:start;
  grid-template-columns:repeat(var(--vcols,3),calc((100% - 44px)/3));padding:14px 0 2px}
/* Lớp dính bỏ padding phải (thẻ trôi ra tận mép) — nhưng hàng chip + ô tìm
   kiếm thì phải giữ lề, nếu không lúc ghim ô tìm kiếm nhảy dính mép màn hình. */.ghp .vrail.is-pinned .vfilter{padding-right:var(--pad)}
/* Chip của dự án đang dẫn đầu khung nhìn. Cố ý KHÁC hẳn chip đang chọn
   (nền cam đặc): đây chỉ là "đang trôi tới đây", không phải bộ lọc đang bật. */.ghp .vchip.now{border-color:var(--coral);color:var(--coral-deep);box-shadow:0 0 0 3px rgba(242,97,28,.16)}.ghp .vchip[aria-pressed="true"].now{box-shadow:0 0 0 3px rgba(242,97,28,.26)}
/* Vạch tiến độ: cho người xem biết còn bao nhiêu thẻ nữa mới nhả trang. */.ghp .pinbar{height:3px;margin:18px var(--pad) 0 0;border-radius:3px;background:rgba(67,86,201,.13);overflow:hidden}.ghp .pinrail:not(.is-pinned) .pinbar{display:none}.ghp .pinbar i{display:block;height:100%;width:0;border-radius:3px;background:linear-gradient(90deg,#4356c9,#f2611c)}
@media (max-width:1023px){.ghp .creedgrid.gpin > *{flex:0 0 calc((100% - 20px)/2)}.ghp .steps.gpin > *{flex:0 0 calc((100% - 12px)/2)}.ghp .pinrail .vgrid.gpin{grid-template-columns:repeat(var(--vcols,3),calc((100% - 22px)/2))}
}
@media (max-width:719px){.ghp .creedgrid.gpin > *{flex:0 0 82%}
}

/* Khối Quy trình nằm trên cùng dải nền sáng (khai chung với .creedsec ở
   trên): tiêu đề gradient chàm→cam, thẻ bước TRẮNG đồng bộ một bảng màu —
   bỏ hẳn lối 5 màu nền pastel ăn theo màu chữ của bản cũ. */.ghp #quytrinh{padding:18px var(--pad) 40px}
/* Tiêu đề Quy trình theo ĐÚNG phong cách tiêu đề khối Quan điểm (anh Bình
   chốt 15/08, đồng bộ nốt thông số 16/08): chữ lớn đậm màu mực, CĂN GIỮA, ẩn
   icon, vế nhấn xuống dòng riêng phủ gradient (khai chung ở trên — lối serif
   nghiêng cam đã bỏ), vạch gradient 2 màu ngăn giữa tiêu đề và câu dẫn. */.ghp #quytrinh .card-h{justify-content:center;padding:34px 0 0}.ghp #quytrinh .card-h .ht{justify-content:center}.ghp #quytrinh .hi{display:none}.ghp #quytrinh .card-h .grad{background:none;-webkit-background-clip:initial;background-clip:initial;-webkit-text-fill-color:currentColor;color:#1f2732;
  font-family:var(--head);font-weight:800;letter-spacing:-.004em;font-size:clamp(30px,4.6vw,52px);line-height:1.12;text-align:center;text-transform:uppercase}.ghp .steplead{margin:0;font-size:15.4px;color:var(--slate);text-align:center}.ghp .steplead::before{content:'';display:block;width:74px;height:3px;margin:18px auto 20px;
  background:linear-gradient(90deg,#4356c9,#f2611c);border-radius:2px}.ghp .steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(318px,1fr));gap:12px;padding:14px 0 6px}.ghp .steps.gpin{display:flex;padding-bottom:6px}.ghp .step{border:1px solid #e9ecf6;border-radius:var(--r-md);padding:18px 16px;display:flex;flex-direction:column;
  background:#fff;box-shadow:0 16px 32px -26px rgba(35,45,110,.5);overflow:hidden}
/* Ảnh thật nằm NÓC thẻ: phá padding bằng margin âm cho chạm 3 mép, huy hiệu
   số kéo lên ĐÈ nửa dưới ảnh (viền trắng để tách khỏi nền ảnh). `cover` 4:3
   để 5 thẻ cao bằng nhau dù ảnh mỗi cái một tỉ lệ. */.ghp .step .art{display:block;margin:-18px -16px 0}.ghp .step .art img{display:block;width:100%;aspect-ratio:4/3;object-fit:cover}.ghp .step .art + .num{margin-top:-25px;position:relative;z-index:1;border:3px solid #fff}
/* Số thứ tự lớn trên đầu thẻ — đọc lướt là biết đang ở bước mấy. */.ghp .step .num{align-self:center;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;
  background:linear-gradient(135deg,#4356c9,#f2611c);color:#fff;font-size:17px;font-weight:900;letter-spacing:.02em;
  box-shadow:0 10px 20px -10px rgba(43,47,119,.75);margin-bottom:12px}.ghp .step b{display:block;font-size:15.6px;margin-bottom:8px;color:var(--ink);text-align:center}.ghp .step p{font-size:13.4px;color:var(--slate);text-align:center;line-height:1.6}.ghp .step p + p{margin-top:7px}
/* "Mục tiêu" là câu chốt của mỗi bước — tách khỏi phần diễn giải bằng vạch mờ
   và đẩy xuống đáy thẻ (margin-top:auto) để các thẻ cùng hàng thẳng chân nhau. */.ghp .step .goal{margin-top:auto;padding-top:11px;border-top:1px dashed rgba(46,58,70,.16);font-size:13.1px;color:var(--ink);
  display:flex;gap:9px;align-items:flex-start;text-align:left}.ghp .step .goal .gi{flex:none;width:26px;height:26px;border-radius:9px;display:grid;place-items:center;background:#eef0fc;color:var(--blue-deep)}.ghp .step .goal .gi svg{width:15px;height:15px}.ghp .step .goal i{font-style:normal;font-weight:800;color:#c8420e}.ghp .stepnote{margin-top:16px;padding:16px 18px;border-radius:var(--r-md);
  background:linear-gradient(120deg,#eef1fd,#fff3ea);
  border:1px solid #e7eaf7;font-size:14.6px;line-height:1.6;color:var(--ink);text-align:center}.ghp .stepnote b{color:#c8420e;font-weight:800}

/* form */
/* Khối form: nền SÁNG tông lạnh nhẹ, điểm nhấn theo đúng cặp màu chàm + cam
   của hai khối kính phía trên (anh Bình chốt 15/08) — bỏ hẳn tông nâu be cũ. */.ghp .leadsec{
  position:relative;overflow:hidden;display:grid;grid-template-columns:1fr 1.08fr;gap:34px;margin:0;
  padding:44px clamp(18px,3vw,42px);border-radius:16px;border:1px solid rgba(255,255,255,.9);
  background:
    radial-gradient(30vmax 22vmax at 100% 0%, rgba(67,86,201,.14), transparent 62%),
    radial-gradient(26vmax 20vmax at 0% 100%, rgba(242,97,28,.12), transparent 62%),
    linear-gradient(118deg,#ffffff 0%,#f4f6ff 52%,#fff4ec 100%);
  box-shadow:0 18px 40px -22px rgba(60,72,150,.3);
}.ghp .leadsec::after{content:'';position:absolute;inset:0 0 auto 0;height:4px;
  background:linear-gradient(90deg,#4356c9,#8b7cf0 46%,#ff7a2f 100%)}.ghp .leadL .eb{font-size:12.5px;letter-spacing:.26em;text-transform:uppercase;color:var(--blue-deep);font-weight:800}.ghp .leadL h2{font-family:var(--head);font-weight:500;font-size:clamp(32px,4.4vw,50px);line-height:1.08;margin-top:6px;color:#1f2732}.ghp .leadL .who{margin-top:16px;font-size:15.4px;color:var(--slate)}.ghp .leadL .who b{color:var(--coral-deep)}.ghp .benefits{margin-top:22px;display:flex;flex-direction:column}.ghp .benefits div{display:flex;align-items:center;gap:14px;padding:15px 0;border-bottom:1px solid rgba(67,86,201,.16);font-size:15.2px;font-weight:600;color:var(--ink)}.ghp .benefits div:last-child{border-bottom:none}.ghp .benefits .ck{width:32px;height:32px;border-radius:50%;flex:none;display:grid;place-items:center;background:linear-gradient(135deg,#4356c9,#f2611c);color:#fff;box-shadow:0 8px 16px -10px rgba(43,47,119,.6)}.ghp .benefits .ck svg{width:15px;height:15px}.ghp .leadL .sign{margin-top:20px;font-style:italic;color:var(--muted);font-size:14px}.ghp .leadR{background:rgba(255,255,255,.94);border:1px solid rgba(228,232,250,.9);border-radius:20px;padding:24px;box-shadow:0 20px 44px -30px rgba(38,46,110,.5)}.ghp .leadR .top{font-size:14.6px;color:var(--slate);margin-bottom:18px}.ghp .fgrid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.ghp .fld{position:relative}.ghp .fld .fi{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none}.ghp .fld .fi svg{width:16px;height:16px}
/* Ô nhập kiểu kính trắng: nền trắng trong, viền mảnh sáng, có mờ nền phía sau.
   Nền panel đã là trắng .92 nên phải thêm viền + bóng chìm, không thì ô nhập
   biến mất vào panel. */.ghp .fld input, .ghp .fld select{width:100%;height:50px;padding:0 14px 0 40px;border-radius:12px;
  border:1px solid rgba(103,116,190,.32);background:rgba(255,255,255,.6);
  -webkit-backdrop-filter:blur(10px) saturate(1.25);backdrop-filter:blur(10px) saturate(1.25);
  box-shadow:inset 0 1px 0 rgba(255,255,255,.9),0 1px 2px rgba(46,58,70,.04);
  color:var(--ink);font:inherit;font-size:14.6px;appearance:none;transition:border-color .16s,box-shadow .16s,background .16s}.ghp .fld input::placeholder{color:#93a0b4}.ghp .fld input:hover, .ghp .fld select:hover{border-color:rgba(255,122,47,.45)}.ghp .fld .ar{position:absolute;right:13px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;transition:transform .16s}.ghp .fld .ar svg{width:15px;height:15px}
/* Hộp chọn dự án: popup của <select> gốc là UI của HỆ ĐIỀU HÀNH — nền xám tối,
   không style được, bung lệch chỗ nhìn rất giật (anh Bình chê 15/08). Chặn
   mousedown để mở panel TỰ DỰNG neo cố định ngay dưới ô: nền trắng, chữ đen,
   hàng đang chọn tick chàm. <select> vẫn là nơi giữ giá trị cho phần gửi form. */.ghp .fld.open .ar{transform:translateY(-50%) rotate(180deg)}.ghp .selpop{position:absolute;top:calc(100% + 8px);left:0;right:0;z-index:40;display:none;
  background:#fff;border:1px solid #e3e7f5;border-radius:14px;padding:6px;
  max-height:290px;overflow:auto;overscroll-behavior:contain;
  box-shadow:0 24px 48px -20px rgba(30,38,100,.35),0 4px 12px rgba(30,38,100,.08)}.ghp .fld.open .selpop{display:block}.ghp .selpop button{display:flex;align-items:center;gap:9px;width:100%;text-align:left;
  padding:10px 12px;border-radius:9px;font-size:14.4px;color:var(--ink);transition:background .12s}.ghp .selpop button:hover{background:#f2f4ff}.ghp .selpop button.on{background:#f2f4ff;color:var(--blue-deep);font-weight:700}.ghp .selpop button .tick{width:16px;flex:none;color:var(--blue-deep);opacity:0}.ghp .selpop button.on .tick{opacity:1}
/* Chạm vào ô nào thì ô đó nổi viền cam + quầng sáng, các ô còn lại giữ nguyên
   — người điền luôn biết mình đang đứng ở đâu. */.ghp .fld input:focus, .ghp .fld select:focus{outline:none;border-color:var(--coral-deep);
  box-shadow:0 0 0 3px rgba(255,122,47,.20),0 8px 20px -12px rgba(242,97,28,.55);background:#fff}.ghp .fld:focus-within .fi{color:var(--coral-deep)}
/* Hàng lựa chọn nhu cầu: các nút GIÃN ĐỀU cho kín MỘT dòng (anh Bình chốt
   15/08) thay vì mỗi nút một bề ngang theo độ dài chữ.
   Cột form chỉ rộng ~538px trên laptop 1440 trong khi sáu nút cần ~562px —
   thiếu đúng vài chục pixel là rớt một nút xuống dòng dưới. Nên ở khổ đó chữ
   và đệm co lại một nhịp cho cả hàng vừa khít; xuống điện thoại thì thôi
   không giãn nữa, để chip xuống dòng tự nhiên (một nút giãn kín bề ngang
   trông như thanh dài lạc lõng). */.ghp .needs{display:flex;flex-wrap:wrap;gap:7px;margin-top:12px}.ghp .need{flex:1 1 auto;text-align:center;white-space:nowrap;font-size:13px;font-weight:700;padding:7px 13px;border-radius:var(--pill);border:1px solid #d3d9f6;background:#f2f4ff;color:var(--blue-deep);transition:.15s}
@media (max-width:1660px){.ghp .needs{gap:5px}.ghp .need{font-size:12.3px;padding:7px 8px}
}.ghp .need[aria-pressed="true"]{background:linear-gradient(135deg,var(--coral),var(--coral-deep));border-color:transparent;color:#fff}.ghp .bigsend{width:100%;height:56px;margin-top:14px;border-radius:12px;font-size:16px;font-weight:800;color:#fff;display:inline-flex;align-items:center;justify-content:center;gap:10px;background:linear-gradient(100deg,var(--coral-deep),var(--coral) 46%,#ffc48a);box-shadow:0 14px 30px -12px rgba(242,97,28,.8);transition:filter .16s ease,transform .16s ease}.ghp .bigsend:hover{filter:brightness(1.05);transform:translateY(-1px)}.ghp .bigsend svg{width:18px;height:18px}.ghp .bigsend:disabled{cursor:default}
/* Dòng báo kết quả gửi form — nằm ngay dưới nút, không đẩy bố cục khi trống. */.ghp .leadmsg{margin:10px 2px 0;font-size:13.4px;font-weight:600;color:var(--muted);min-height:1px}.ghp .leadmsg:empty{display:none}.ghp .leadmsg.ok{color:var(--mint)}.ghp .leadmsg.err{color:#d93a3a}.ghp .leadmsg.warn{color:var(--gold)}

/* MÀN CẢM ƠN — dùng chung cho form cuối trang lẫn thiệp mời nổi (anh Bình chốt
   15/08: điền ở đâu cũng nhận đúng MỘT lời cảm ơn ấy). Đặt tại chỗ của form vừa
   điền, không cuộn khách đi đâu cả: người ta vừa bấm gửi thì câu trả lời phải
   hiện ngay dưới ngón tay, không phải ở màn hình khác. */.ghp .ghthanks{text-align:center;animation:ghThanksIn .42s ease both}.ghp .ghthanks .tick{width:52px;height:52px;margin:0 auto 14px;border-radius:50%;display:grid;place-items:center;
  background:linear-gradient(150deg,var(--coral),var(--coral-deep));box-shadow:0 12px 26px -14px rgba(207,71,18,.9)}.ghp .ghthanks .tick svg{width:26px;height:26px;stroke:#fff;fill:none;stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round}.ghp .ghthanks h3{font-size:clamp(17px,2.2vw,21px);font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--ink)}.ghp .ghthanks p{margin-top:9px;font-size:14.6px;line-height:1.62;color:var(--slate)}.ghp .ghthanks .pill{display:inline-flex;flex-wrap:wrap;justify-content:center;gap:6px 14px;margin-top:14px;padding:9px 16px;
  border-radius:var(--pill);background:#f2f4ff;font-size:12.6px;font-weight:700;color:#4356c9}.ghp .ghthanks .bye{margin-top:14px;font-size:15.4px;font-weight:800;color:var(--coral-deep)}
@keyframes ghThanksIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@media (prefers-reduced-motion:reduce){.ghp .ghthanks{animation:none} }.ghp .pledge{margin-top:20px;padding-top:18px;border-top:1px solid #e2e6f7}.ghp .pledge .hd{display:flex;align-items:center;gap:9px;font-size:12.6px;letter-spacing:.16em;text-transform:uppercase;font-weight:800;color:var(--blue-deep)}.ghp .pledge .hd svg{width:16px;height:16px}.ghp .pledge .it{display:flex;align-items:center;gap:11px;margin-top:12px;font-size:14.2px;color:var(--ink)}.ghp .pledge .it .pi{width:30px;height:30px;border-radius:9px;flex:none;display:grid;place-items:center;background:rgba(67,86,201,.1);color:var(--blue-deep)}.ghp .pledge .it .pi svg{width:15px;height:15px}.ghp .fine{font-size:12.4px;color:var(--muted);margin-top:12px}

/* liên hệ */.ghp .contact{display:grid;grid-template-columns:1fr 190px;gap:14px;padding:14px 0 6px}.ghp .clines{display:grid;grid-template-columns:1fr 1fr;gap:9px}.ghp .cline{display:flex;gap:11px;align-items:center;border:1px solid var(--line);border-radius:var(--r-md);padding:12px 14px;background:#fff}.ghp .cline:hover{border-color:var(--coral)}.ghp .cline .ic{width:34px;height:34px;border-radius:var(--r-sm);flex:none;display:grid;place-items:center;background:var(--tint-co);color:var(--coral-deep)}.ghp .cline:nth-child(2) .ic{background:var(--tint-bl);color:var(--blue-deep)}.ghp .cline:nth-child(3) .ic{background:var(--tint-mi);color:var(--mint)}.ghp .cline:nth-child(4) .ic{background:var(--tint-vi);color:var(--violet)}.ghp .cline .ic svg{width:16px;height:16px}.ghp .cline b{display:block;font-size:14.2px}.ghp .cline small{font-size:12.4px;color:var(--muted);font-family:var(--mono)}.ghp .qr{border:1px solid var(--line);border-radius:var(--r-md);display:grid;place-items:center;gap:9px;padding:14px;text-align:center;background:#fff}.ghp .qr .code{width:104px;height:104px;border-radius:var(--r-sm);background:repeating-conic-gradient(var(--ink) 0% 25%,transparent 0% 50%) 0 0/15px 15px;opacity:.85}.ghp .qr small{font-size:12px;color:var(--muted)}

/* CTA nổi */.ghp .bubbles{position:fixed;right:20px;bottom:24px;z-index:60;display:flex;flex-direction:column;gap:9px}.ghp .bub{width:50px;height:50px;border-radius:50%;background:#fff;border:1px solid var(--line);display:grid;place-items:center;box-shadow:var(--sh-card);transition:transform .16s ease}.ghp .bub svg{width:20px;height:20px;color:var(--coral-deep)}.ghp .bub:hover{transform:scale(1.07)}.ghp .bub.main{background:linear-gradient(135deg,var(--coral),var(--coral-deep));border-color:transparent;box-shadow:var(--sh-coral)}.ghp .bub.main svg{color:#fff}

/* ---------- cụm nút nổi: chuyển động ---------- */
/* Vào trang thì ba nút nảy lên lần lượt từ dưới, mỗi nút lệch 90ms. */.ghp .bubbles .bub{animation:bubIn .5s cubic-bezier(.22,1.2,.36,1) both}.ghp .bubbles .bub:nth-child(1){animation-delay:.20s}.ghp .bubbles .bub:nth-child(2){animation-delay:.29s}.ghp .bubbles .bub:nth-child(3){animation-delay:.38s}
@keyframes bubIn{from{opacity:0;transform:translateY(14px) scale(.86)}to{opacity:1;transform:none}}
/* Nút chính thở nhẹ + toả một vòng sóng, đủ để mắt bắt được mà không nhấp nháy. */.ghp .bub.main{position:relative;animation:bubIn .5s cubic-bezier(.22,1.2,.36,1) both,bubBreath 3.2s ease-in-out 1.2s infinite}.ghp .bub.main:before{content:'';position:absolute;inset:0;border-radius:50%;border:2px solid var(--coral);
  animation:bubRing 3.2s ease-out 1.6s infinite;pointer-events:none}
@keyframes bubBreath{0%,72%,100%{transform:none}82%{transform:scale(1.06)}92%{transform:scale(.99)}}
@keyframes bubRing{0%{opacity:.55;transform:scale(1)}70%,100%{opacity:0;transform:scale(1.75)}}.ghp .bub.main:hover{animation-play-state:paused}
/* Hai nút phụ cũng có nhịp riêng trên ICON: ống nghe rung như chuông đổ, bong
   bóng chat nhún lên một nhịp. Cùng chu kỳ 3.2s với nút chính nhưng lệch pha —
   mỗi nút cựa một lúc, cả cụm không bao giờ nhấp nháy đồng loạt. */.ghp .bub svg{transform-origin:50% 50%}.ghp .bubbles .bub:nth-child(1) svg{animation:bubCall 3.2s ease-in-out 1.9s infinite}
@keyframes bubCall{0%,40%,76%,100%{transform:none}
  46%{transform:rotate(-13deg)}52%{transform:rotate(11deg)}58%{transform:rotate(-8deg)}64%{transform:rotate(5deg)}70%{transform:rotate(-2deg)}}.ghp .bubbles .bub:nth-child(2) svg{animation:bubChat 3.2s ease-in-out 2.55s infinite}
@keyframes bubChat{0%,40%,80%,100%{transform:none}
  50%{transform:translateY(-2.5px) scale(1.1)}62%{transform:translateY(.5px) scale(.96)}70%{transform:translateY(-1px) scale(1.03)}}.ghp .bub:hover svg{animation-play-state:paused}

/* ---------- thiệp mời liên hệ (hiện sau 5 giây) ---------- */
/* Thiệp nằm GIỮA màn hình, có màn phủ mờ phía sau — nó là lời mời chào, phải
   được nhìn thấy hẳn hoi chứ không nép một góc. Bề ngang chốt 330px, không
   trải hết ngang máy điện thoại. */.ghp .hail{position:fixed;inset:0;z-index:61;display:grid;place-items:center;padding:20px;
  background:rgba(24,30,38,.32);-webkit-backdrop-filter:blur(3px);backdrop-filter:blur(3px);
  opacity:0;pointer-events:none;transition:opacity .3s ease}.ghp .hail.on{opacity:1;pointer-events:auto}.ghp .hcard{position:relative;width:min(330px,100%);
  background:rgba(255,255,255,.92);-webkit-backdrop-filter:blur(20px) saturate(1.3);backdrop-filter:blur(20px) saturate(1.3);
  border:1px solid rgba(255,255,255,.9);border-radius:20px;padding:18px 18px 16px;
  box-shadow:0 34px 70px -30px rgba(20,26,34,.7),0 2px 6px rgba(46,58,70,.08);
  transform:translateY(16px) scale(.94);transition:transform .34s cubic-bezier(.22,1.2,.36,1)}.ghp .hail.on .hcard{transform:none}.ghp .hail .x{position:absolute;top:9px;right:9px;width:26px;height:26px;border-radius:50%;display:grid;place-items:center;
  color:var(--muted);font-size:16px;line-height:1;background:rgba(46,58,70,.06)}.ghp .hail .x:hover{background:rgba(46,58,70,.12);color:var(--ink)}.ghp .hail .who{display:flex;align-items:center;gap:10px;margin-bottom:10px}.ghp .hail .who .av{width:38px;height:38px;border-radius:50%;flex:none;background:linear-gradient(150deg,var(--coral),var(--coral-deep));
  color:#fff;display:grid;place-items:center;font-weight:800;font-size:13.5px;background-size:cover;background-position:center}.ghp .hail .who b{display:block;font-size:14.4px;line-height:1.25}.ghp .hail .who small{display:block;font-size:12.2px;color:var(--muted)}.ghp .hail .call{display:flex;align-items:center;justify-content:center;gap:8px;height:42px;border-radius:var(--pill);
  background:linear-gradient(135deg,var(--coral),var(--coral-deep));color:#fff;font-weight:800;font-size:14.2px;box-shadow:var(--sh-coral)}.ghp .hail .call svg{width:17px;height:17px}.ghp .hail .or{display:flex;align-items:center;gap:9px;margin:11px 0 9px;font-size:11.6px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em}.ghp .hail .or:before, .ghp .hail .or:after{content:'';flex:1;height:1px;background:var(--line)}.ghp .hail form{display:grid;gap:7px}.ghp .hail input{height:40px;border-radius:11px;border:1px solid rgba(206,190,175,.55);background:rgba(255,255,255,.6);
  padding:0 13px;font:inherit;font-size:13.8px;color:var(--ink);transition:border-color .16s,box-shadow .16s}.ghp .hail input:focus{outline:none;border-color:var(--coral-deep);box-shadow:0 0 0 3px rgba(255,122,47,.18);background:#fff}
/* Nền trắng chữ đỏ: nút phụ, không tranh chấp với nút "Gọi ngay" màu cam đặc. */.ghp .hail button[type=submit]{height:42px;border-radius:11px;background:#fff;color:#cf4712;
  border:1.5px solid rgba(207,71,18,.38);font-weight:800;font-size:14px;transition:border-color .16s,box-shadow .16s,background .16s}.ghp .hail button[type=submit]:hover{border-color:#cf4712;box-shadow:0 10px 22px -14px rgba(207,71,18,.8)}
/* Trên máy tính phóng to gấp đôi: 330px giữa màn hình 1440 trông lọt thỏm.
   Hai ô nhập xếp cạnh nhau cho khỏi bị kéo dài lêu nghêu. */
@media (min-width:1024px){.ghp .hcard{width:660px;padding:30px 32px 28px;border-radius:26px}.ghp .hail .x{top:14px;right:14px;width:34px;height:34px;font-size:20px}.ghp .hail .who{gap:14px;margin-bottom:18px}.ghp .hail .who .av{width:60px;height:60px;font-size:20px}.ghp .hail .who b{font-size:21px}.ghp .hail .who small{font-size:15px;margin-top:2px}.ghp .hail .call{height:62px;font-size:19px;gap:11px}.ghp .hail .call svg{width:23px;height:23px}.ghp .hail .or{margin:18px 0 14px;font-size:13px}.ghp .hail form{grid-template-columns:1fr 1fr;gap:12px}.ghp .hail input{height:54px;border-radius:14px;padding:0 18px;font-size:16px}.ghp .hail button[type=submit]{grid-column:1 / -1;height:54px;border-radius:14px;font-size:17px}
}
/* ---------- chế độ sửa tại chỗ (chỉ khi ?edit=1) ---------- */.ghp .gh-edit [data-gh-section]{position:relative}.ghp .gh-edit [data-gh-section]:after{content:'';position:absolute;inset:-6px;border:2px dashed transparent;border-radius:16px;pointer-events:none;transition:border-color .15s;z-index:5}.ghp .gh-edit [data-gh-section]:hover:after{border-color:rgba(242,97,28,.55)}.ghp .gh-editbtn{position:absolute;top:8px;right:8px;z-index:70;display:inline-flex;align-items:center;gap:6px;
  padding:7px 13px;border-radius:999px;border:0;cursor:pointer;
  background:linear-gradient(135deg,#ff7a2f,#f2611c);color:#fff;font:700 12.5px/1 inherit;
  box-shadow:0 10px 22px -12px rgba(242,97,28,.9);opacity:0;transform:translateY(-4px);transition:opacity .16s,transform .16s}.ghp .gh-edit [data-gh-section]:hover > .gh-editbtn, .ghp .gh-editbtn:focus-visible{opacity:1;transform:none}.ghp .gh-editbtn svg{width:13px;height:13px}

/* ---- sửa TẠI CHỖ (v2): bấm thẳng vào chữ / ảnh / video, không mở form ---- */.ghp .gh-edit .gh-txt{cursor:text;border-radius:6px;transition:box-shadow .12s}.ghp .gh-edit .gh-txt:hover{box-shadow:0 0 0 2px rgba(242,97,28,.45);background:rgba(255,240,230,.35)}.ghp .gh-edit .gh-txt.gh-on{box-shadow:0 0 0 2px rgba(242,97,28,.95);background:rgba(255,255,255,.85);color:#1b2433;outline:0;min-width:24px}
/* nút nhỏ đè lên ảnh: "Đổi ảnh bìa", "Đổi ảnh"… */.ghp .gh-imgwrap{position:relative}.ghp .gh-imgbtn{position:absolute;z-index:60;display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border:0;border-radius:999px;
  background:rgba(15,23,34,.82);color:#fff;font:700 12.5px/1 inherit;cursor:pointer;opacity:0;transition:opacity .15s;
  -webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px);white-space:nowrap}.ghp .gh-imgbtn svg{width:14px;height:14px}.ghp .gh-imgwrap:hover .gh-imgbtn, .ghp .gh-imgbtn:focus-visible{opacity:1}
/* công cụ trên từng thẻ video */.ghp .gh-vtools{position:absolute;top:8px;left:8px;z-index:60;display:flex;gap:6px;opacity:0;transition:opacity .15s}.ghp .vitem:hover .gh-vtools, .ghp .gh-vtools:focus-within{opacity:1}.ghp .gh-vtools button{border:0;border-radius:999px;padding:6px 11px;font:700 12px/1 inherit;cursor:pointer;color:#fff;background:rgba(15,23,34,.82)}.ghp .gh-vtools button.del{background:rgba(218,5,9,.88)}
/* Nút "Thêm video" (anh Bình chốt 18/08): trước đây là một nút gạch đứt nhỏ nép
   sát mép trái, lẫn vào khung gạch đứt của cả khối nên chủ trang phải đi tìm.
   Nay đứng GIỮA, to hơn, có dấu ＋ tròn màu thương hiệu và quầng cam nhẹ —
   `width:fit-content` + `margin:auto` mới căn giữa được (nút vốn inline-flex,
   text-align của khối cha không kéo nó vào giữa). */.ghp .gh-addvideo{display:flex;align-items:center;justify-content:center;gap:11px;
  width:fit-content;max-width:100%;margin:20px auto 6px;
  border:2px dashed rgba(242,97,28,.5);border-radius:var(--pill);
  background:linear-gradient(180deg,#fff,#fff4ed);
  color:var(--coral-deep);font:800 15.4px/1.25 inherit;padding:15px 30px;cursor:pointer;text-align:center;
  box-shadow:0 12px 30px -20px rgba(242,97,28,.75);
  transition:transform .16s ease,box-shadow .2s ease,border-color .2s ease,background .2s ease}.ghp .gh-addvideo .ic{flex:none;display:grid;place-items:center;width:30px;height:30px;border-radius:50%;
  background:linear-gradient(135deg,var(--coral),var(--coral-deep));color:#fff;font-size:17px;line-height:1;
  box-shadow:0 6px 14px -8px rgba(242,97,28,.9)}.ghp .gh-addvideo:hover{background:linear-gradient(180deg,#fff7f2,#ffe9dc);border-color:rgba(242,97,28,.85);
  transform:translateY(-1px);box-shadow:0 16px 34px -20px rgba(242,97,28,.9)}
@media (max-width:600px){.ghp .gh-addvideo{width:100%;padding:14px 18px;font-size:14.2px;gap:9px}
}
@media (prefers-reduced-motion:reduce){.ghp .gh-addvideo{transition:none} }
/* bảng thêm/sửa video — nằm NGAY TRONG khối video, không phải popup */.ghp .gh-vpanel{display:none;border:1.5px solid #ffd9c2;border-radius:16px;background:#fffaf6;padding:16px;margin:12px 0}.ghp .gh-vpanel.on{display:block}.ghp .gh-vpanel .row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px}
/* Ô thời lượng do hệ thống điền ⇒ bày như một ô CHỈ ĐỌC: nền xám nhạt, con trỏ
   không đổi thành dấu nháy, để chủ trang khỏi bấm vào rồi tưởng mình gõ được. */.ghp .gh-vpanel input[readonly]{background:#f4f6f8;color:var(--slate);cursor:default}.ghp .gh-vpanel .row.one{grid-template-columns:1fr}.ghp .gh-vpanel label{display:block;font:700 12px/1.2 inherit;color:#6b7280;margin:0 0 5px}.ghp .gh-vpanel input, .ghp .gh-vpanel select{width:100%;height:40px;border:1.5px solid #e5e7eb;border-radius:10px;padding:0 12px;font:600 13.5px/1 inherit;background:#fff;color:#1b2433}.ghp .gh-vpanel input:focus, .ghp .gh-vpanel select:focus{outline:0;border-color:#f2611c}.ghp .gh-vpanel .thumb{width:132px;height:76px;border-radius:10px;background:#eef1f6 center/cover no-repeat;border:1.5px solid #e5e7eb;flex:0 0 auto}.ghp .gh-vpanel .thumbrow{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.ghp .gh-vpanel .acts{display:flex;gap:8px;justify-content:flex-end;margin-top:6px}.ghp .gh-vpanel .acts button{border:0;border-radius:999px;padding:10px 18px;font:800 13px/1 inherit;cursor:pointer}.ghp .gh-vpanel .acts .ok{background:linear-gradient(135deg,#ff7a2f,#f2611c);color:#fff}.ghp .gh-vpanel .acts .no{background:#eef1f6;color:#374151}.ghp .gh-vpanel .mini{border:1.5px solid #e5e7eb;background:#fff;color:#374151;border-radius:999px;padding:8px 14px;font:700 12.5px/1 inherit;cursor:pointer}.ghp .gh-vpanel .hint{font:600 12px/1.4 inherit;color:#9aa3b2;margin:6px 0 0}
@media (max-width:640px){.ghp .gh-vpanel .row{grid-template-columns:1fr}}
/* thẻ báo "Đã lưu" nổi dưới đáy */.ghp .gh-chip{position:fixed;left:50%;bottom:20px;transform:translateX(-50%) translateY(6px);z-index:220;background:#0f1722;color:#fff;
  padding:9px 16px;border-radius:999px;font:700 13px/1 inherit;opacity:0;transition:opacity .18s,transform .18s;pointer-events:none;box-shadow:0 12px 30px -12px rgba(0,0,0,.5)}.ghp .gh-chip.on{opacity:1;transform:translateX(-50%) translateY(0)}.ghp .gh-chip.err{background:#da0509}

/* ---- hộp phát video (dùng cả ngoài chế độ sửa) ---- */
/* Trình chiếu video FULL MÀN HÌNH: bấm phát là phủ kín cửa sổ, thanh điều khiển
   tự dựng nổi giữa đáy, ⤢ nâng lên fullscreen trình duyệt, ✕/Esc đóng. */.ghp .gh-cine{position:fixed;inset:0;z-index:240;background:rgba(5,8,14,.97)}.ghp .gh-cine .cap{position:absolute;top:16px;left:20px;right:120px;z-index:3;color:#fff;font-size:14.5px;font-weight:700;text-shadow:0 1px 8px rgba(0,0,0,.6);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;opacity:0;transition:opacity .22s}.ghp .gh-cine.ctl .cap{opacity:1}.ghp .gh-cine .med, .ghp .gh-cine .med>div, .ghp .gh-cine iframe, .ghp .gh-cine video{position:absolute;inset:0;width:100%;height:100%;border:0;display:block;background:#000}.ghp .gh-cine video{object-fit:contain}
/* Clip dọc: thu khung phát về đúng 9:16 giữa màn thay vì kéo hết bề ngang —
   iframe TikTok/YouTube khi bị ép khung ngang sẽ tự chèn hai dải đen to đùng,
   nhìn như video lỗi. Trên màn hẹp (điện thoại dựng đứng) thì 9:16 vốn đã cao
   hơn màn nên `height:100%` giữ lại, bề ngang tự co theo. */.ghp .gh-cine.vert .med{left:50%;right:auto;transform:translateX(-50%);width:auto;height:100%;aspect-ratio:9/16;max-width:100%}.ghp .gh-cine.vert .med>div, .ghp .gh-cine.vert iframe, .ghp .gh-cine.vert video{width:100%;height:100%}.ghp .gh-cine .shield{position:absolute;inset:0;z-index:2;cursor:pointer}.ghp .gh-cine .gh-tr{position:absolute;top:12px;right:14px;z-index:4;display:flex;gap:8px}.ghp .gh-cine .gh-tr button{width:36px;height:36px;border:0;border-radius:999px;background:rgba(10,14,20,.55);color:#fff;cursor:pointer;display:grid;place-items:center;font:800 15px/1 inherit;-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);transition:background .15s}.ghp .gh-cine .gh-tr button:hover{background:rgba(10,14,20,.85)}.ghp .gh-cine .gh-tr svg{width:17px;height:17px}.ghp .gh-vc{position:absolute;left:50%;bottom:22px;transform:translateX(-50%);z-index:3;width:min(680px,calc(100% - 32px));background:rgba(12,15,22,.66);-webkit-backdrop-filter:blur(12px);backdrop-filter:blur(12px);border-radius:14px;padding:13px 20px 11px;color:#fff;display:flex;flex-direction:column;gap:9px;opacity:0;pointer-events:none;transition:opacity .22s}.ghp .gh-cine.ctl .gh-vc{opacity:1;pointer-events:auto}.ghp .gh-vc .r1, .ghp .gh-vc .r2{display:flex;align-items:center;gap:13px}.ghp .gh-vc .tc, .ghp .gh-vc .td{font-family:var(--mono);font-size:12.5px;min-width:38px}.ghp .gh-vc .td{text-align:right}.ghp .gh-vc input[type=range]{-webkit-appearance:none;appearance:none;height:4px;border-radius:99px;background:rgba(255,255,255,.34);outline:0;cursor:pointer;margin:0;padding:0}.ghp .gh-vc input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;appearance:none;width:12px;height:12px;border-radius:50%;background:#fff;border:0;box-shadow:0 1px 5px rgba(0,0,0,.45)}.ghp .gh-vc input[type=range]::-moz-range-thumb{width:12px;height:12px;border-radius:50%;background:#fff;border:0}.ghp .gh-vc .seek{flex:1}.ghp .gh-vc .vol{width:88px}.ghp .gh-vc .r2 button{border:0;background:transparent;color:#fff;cursor:pointer;display:grid;place-items:center;padding:6px;border-radius:8px}.ghp .gh-vc .r2 button svg{width:18px;height:18px}.ghp .gh-vc .r2 button:hover{background:rgba(255,255,255,.12)}.ghp .gh-vc .gap{flex:1}.ghp .gh-vc .rt{font:700 13px/1 inherit;padding:8px 10px}.ghp .gh-vc .rt.on{background:rgba(5,7,12,.85)}.ghp .gh-vc .rt.on:hover{background:rgba(5,7,12,.85)}.ghp .gh-cine:fullscreen{background:#000}.ghp .gh-cine:-webkit-full-screen{background:#000}
@media (max-width:640px){.ghp .gh-vc{padding:10px 13px 9px;gap:7px;border-radius:12px}.ghp .gh-vc .vol{display:none}.ghp .gh-vc .rt{padding:7px 8px;font-size:12px}
}
@media (prefers-reduced-motion:reduce){.ghp .bubbles .bub, .ghp .bub.main, .ghp .bubbles .bub svg{animation:none}.ghp .bub.main:before{display:none}.ghp .hail, .ghp .hcard{transition:none}
}

/* dải CTA cuối trang — cá nhân hoá theo từng người */.ghp .ctaband{
  position:relative;overflow:hidden;border-radius:24px;margin:8px 0 4px;padding:60px 40px;text-align:center;color:#fff;
  background:#0f1722 url('/sotay/welcome_bg_h.jpg') center/cover no-repeat;
  text-shadow:0 2px 14px rgba(0,0,0,.62), 0 1px 3px rgba(0,0,0,.55);
}
@media (max-width:820px){.ghp .ctaband{background-image:url('/sotay/welcome_bg_v.jpg');padding:54px 22px} }.ghp .ctaband .eb{color:#e9c46a;font-weight:800;letter-spacing:3px;font-size:13px;text-transform:uppercase}
/* Trần bề ngang 24ch là đo theo Inter; Optima chữ rộng hơn nên câu trắng vỡ
   thành 3 dòng (anh Bình soi ra 18/08). Anh chốt: vế TRẮNG 2 dòng, vế XANH
   ĐÚNG 1 DÒNG. Vế xanh dài 33ch ⇒ trần phải ≥34ch mới đủ một dòng; lấy 36ch
   cho dư. `text-wrap:balance` giữ vế trắng chia đều 2 dòng (để mặc định thì
   nó nhồi đầy dòng trên rồi cắt giữa cụm "bất động sản"). ĐỪNG hạ lại xuống
   dưới 34ch — vế xanh tụt về 2 dòng ngay. */.ghp .ctaband h2{font-size:clamp(28px,4.5vw,50px);font-weight:900;letter-spacing:-.004em;margin:12px 0;color:#fff;max-width:36ch;text-wrap:balance;margin-left:auto;margin-right:auto}
/* Hệ số vw hạ 5 → 4.5 CHÍNH VÌ vế xanh: nó dài 33ch ≈ 18em, mà bề ngang còn
   lại của khối (đã trừ --pad + padding 22px) ở màn 800-1100px không đủ cho
   18em khi chữ ăn trọn 5vw ⇒ tụt xuống 2 dòng. Màn ≥1112px vẫn chạm trần 50px
   nên nhìn y như cũ. Điện thoại thì 1 dòng là bất khả (33ch ở 375px ra chữ
   ~11px) — chấp nhận xuống dòng. */.ghp .ctaband .hl{display:block;color:#b7e61d}.ghp .ctaband p{color:rgba(255,255,255,.8);max-width:640px;margin:0 auto 24px;font-size:15.2px}.ghp .ctaband .acts{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}.ghp .ctaband .cta1{display:inline-flex;align-items:center;gap:10px;background:#fff;color:#da0509;padding:14px 30px;border-radius:30px;font-weight:800;font-size:15.2px;text-shadow:none;transition:.25s}.ghp .ctaband .cta1:hover{transform:translateY(-3px);box-shadow:0 14px 34px rgba(0,0,0,.3)}.ghp .ctaband .cta2{display:inline-flex;align-items:center;gap:10px;border:1px solid rgba(255,255,255,.6);color:#fff;padding:14px 26px;border-radius:30px;font-weight:700;font-size:15.2px;-webkit-backdrop-filter:blur(6px);backdrop-filter:blur(6px);transition:.25s}.ghp .ctaband .cta2:hover{transform:translateY(-3px);background:rgba(255,255,255,.14)}.ghp .ctaband svg{width:17px;height:17px}

/* chân trang cá nhân */.ghp .foot{border-top:1px solid var(--line);background:linear-gradient(180deg,#fff,#fffaf6)}.ghp .footin{max-width:none;margin:0;padding:34px var(--pad) 30px;display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:26px}.ghp .fbrand{display:flex;align-items:center;gap:12px}.ghp .fbrand .m{width:46px;height:46px;border-radius:50%;background:linear-gradient(150deg,var(--coral),var(--coral-deep));color:#fff;display:grid;place-items:center;font-weight:800;font-size:17px}
/* Có ảnh Hồ sơ thì ô tròn hiện ẢNH THẬT (đồng bộ avatar, anh Bình chốt 15/08). */.ghp .fbrand .m.mimg{background-size:cover;background-position:center top}.ghp .fbrand b{font-size:17px;display:block}.ghp .fbrand small{font-family:var(--mono);font-size:12.2px;color:var(--muted)}.ghp .fbrand small .cic{display:inline-block;vertical-align:-2px;margin-right:4px}.ghp .fbrand small .cic svg{width:12px;height:12px}.ghp .footin p{font-size:13.8px;color:var(--slate);margin-top:12px;max-width:46ch}.ghp .fhot{margin-top:12px;font-size:15px;font-weight:800;color:var(--coral-deep)}.ghp .footin h4{font-size:12.4px;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:12px}.ghp .flist a{display:flex;gap:9px;align-items:flex-start;font-size:13.8px;color:var(--slate);margin-bottom:9px}.ghp .flist a:hover{color:var(--coral-deep)}.ghp .flist i{width:7px;height:7px;border-radius:50%;background:var(--coral);margin-top:7px;flex:none}.ghp .footbot{border-top:1px solid var(--line);padding:13px 16px;text-align:center;font-size:12.6px;color:var(--muted)}.ghp .rv{opacity:0;transform:translateY(12px)}.ghp .rv.in{opacity:1;transform:none;transition:opacity .5s ease,transform .5s ease}
@media (prefers-reduced-motion:reduce){.ghp .rv, .ghp .rv.in{opacity:1;transform:none;transition:none}.ghp .b, .ghp .play, .ghp .bub, .ghp .cfi, .ghp .dcard{transition:none}
  /* Vòng toả của nút phát là chuyển động lặp vô hạn — người bật "giảm chuyển
     động" phải thấy một vòng sáng ĐỨNG YÊN, không phải nhịp nở liên tục. */.ghp .play::before{animation:none;opacity:.35}.ghp .play::after{animation:none;opacity:0}}

@media (min-width:1500px){.ghp .diary{grid-template-columns:repeat(4,1fr)}.ghp .introwrap{grid-template-columns:1fr 1fr;gap:36px}
}
@media (max-width:1240px){.ghp .creedgrid{grid-template-columns:repeat(2,1fr)} }
/* Khổ máy tính bảng: khung chữ hẹp lại nên hàng nút thụt vào tới chỗ ảnh đại
   diện (ảnh này nhô lên đúng 1/3 chiều cao của nó vào ảnh bìa). Neo theo PX
   thay vì % để mọi chiều cao ảnh bìa đều dừng ngay TRÊN vành ảnh đại diện —
   tính theo % thì màn thấp lại đè lên. */
@media (max-width:900px){.ghp .coverblock{bottom:calc(var(--ava)/3 + 14px)} }
@media (max-width:1040px){.ghp .body{padding-bottom:40px}.ghp .introwrap{grid-template-columns:1fr}
  /* Anh Bình chốt 14/08: giữ CỘT ICON DỌC bên phải ở mọi khổ màn hình, bỏ hẳn
     thanh 3 nút dưới chân trang — cột icon là dấu nhận diện của trang. */.ghp .bubbles{right:12px;bottom:calc(16px + env(safe-area-inset-bottom));gap:8px}.ghp .bub{width:46px;height:46px}.ghp .bub svg{width:19px;height:19px}.ghp .footin{grid-template-columns:1fr}.ghp .facts{grid-template-columns:repeat(3,1fr)}.ghp .vgrid, .ghp .diary{grid-template-columns:repeat(2,1fr)}.ghp .leadsec{grid-template-columns:1fr;padding:28px 22px}.ghp .idrow{flex-wrap:wrap}.ghp .idmain{flex:1 1 260px}.ghp .idacts{width:100%;justify-content:flex-start;padding-bottom:0;padding-top:2px}
}
@media (max-width:720px){.ghp{--pad:16px}
  /* Điện thoại: sáu nút không nằm nổi một dòng, nên chia HAI hàng BA nút
     (anh Bình chốt 16/08) — đúng hai nhóm sẵn có: hàng trên là mục đích mua,
     hàng dưới là khoảng giá, không phải đảo thứ tự gì.
     Phải là LƯỚI 3 cột chứ không thể để flex tự xuống dòng: flex xếp đầy được
     bao nhiêu thì xếp, nên máy màn to (430px) ra 4+2 với hai nút hàng dưới
     phình gấp đôi. Cột `auto` = rộng theo chữ dài nhất của chính cột đó, phần
     dư chia đều — vẫn cân theo độ dài chữ, không phải ba cột bằng chằn chặn. */.ghp .needs{display:grid;grid-template-columns:repeat(3,auto);gap:6px}.ghp .need{font-size:11.6px;padding:7px 8px}
  /* Máy nhỏ (SE, 320px): ba cột không đủ chỗ cho "Cần vay ngân hàng" — về
     hai cột thay vì để chữ tràn ra ngoài mép. */
  @media (max-width:360px){.ghp .needs{grid-template-columns:repeat(2,auto)}
  }
  /* Điện thoại: 16/10 y như bìa dự án. Khung cũ (clamp 260–360px) trên máy
     390px là gần VUÔNG nên ảnh ngang bị xén mất hai bên rất nặng. */.ghp{--cover-h:calc(100vw * 10 / 16)}
  /* Điện thoại: GỠ hai nút trên ảnh bìa (anh Bình chốt 16/08) — chúng xuống 2
     hàng chiếm gần hết ảnh, mà ngay dưới đã có cột icon + thanh "Nhận bảng
     hàng" nên không mất lối bấm. Ảnh bìa chỉ còn eyebrow + tiêu đề. */.ghp .coverbtns{display:none}.ghp .idrow{align-items:flex-start;gap:12px;padding:calc(var(--ava)*0.72) var(--pad) 10px}.ghp .ava{left:var(--ava-left)}.ghp{--ava:132px;--ava-left:12px}.ghp .avatar{border-width:4px}.ghp .ava .live{border-width:3px}.ghp .idmain{padding-top:6px}.ghp .idmain h1{font-size:22px}.ghp .idacts{width:100%;flex-wrap:wrap;justify-content:space-between;padding-bottom:0}.ghp .ib{width:44px;height:44px}.ghp .ib.main{width:48px;height:48px}.ghp .ib .tip{display:none}.ghp .steps, .ghp .clines, .ghp .contact, .ghp .creedgrid, .ghp .vgrid, .ghp .fgrid, .ghp .inforows, .ghp .diary{grid-template-columns:1fr}
  /* Thẻ Quy trình trên điện thoại: hẹp lại còn 75% bề ngang (anh Bình chốt
     14/08). Thẻ cao mà chiếm hết bề ngang thì hình minh hoạ bị kéo to, đọc như
     một tấm áp phích chứ không phải một bước trong quy trình; hẹp lại còn lộ
     mép thẻ sau nên nhìn là biết vuốt được.
     PHẢI sửa `flex` chứ không phải `width`: khối này là dải chạy ngang theo
     cuộn (.gpin), flex-basis ở trên đè mọi khai báo width. */.ghp .steps.gpin > .step{flex:0 0 75%}.ghp .creedsec{padding:34px var(--pad) 30px}.ghp .leadsec{padding:24px 18px}.ghp .leadR{padding:18px}.ghp .facts{grid-template-columns:repeat(2,1fr)}
  /* Ba nút gợi ý: chia đều trên màn hẹp thì nhãn vỡ thành 3 dòng chữ dựng
     đứng. Cho chúng giữ nguyên bề ngang theo chữ rồi trượt ngang. */.ghp .composer-acts{overflow-x:auto;scrollbar-width:none;gap:8px}.ghp .composer-acts::-webkit-scrollbar{display:none}.ghp .composer-acts button{flex:0 0 auto;white-space:nowrap;padding:11px 14px;font-size:13.2px}.ghp .tabs{top:8px}.ghp .tabsin{padding:0 10px}
  /* Thanh tab DÍNH suốt cả trang, mà `backdrop-filter` trên một khối dính là
     món đắt nhất lúc cuộn: mỗi khung hình máy phải chụp lại mảng nền phía sau
     rồi làm mờ 18px + tăng bão hoà. Ngay dưới nó lại là VIDEO BÌA đang phát
     nên nền đổi liên tục, không có gì để dùng lại từ khung hình trước — đúng
     chỗ làm cuộn khựng trên điện thoại (anh Bình báo 17/08).
     Điện thoại bỏ hẳn lớp mờ, đổi sang nền đục: nhìn gần như y hệt (nền trang
     vốn đã sáng), mà cuộn nhẹ hẳn. Máy tính giữ nguyên kính mờ. */.ghp .tabshell{background:rgba(238,241,246,.94);
    -webkit-backdrop-filter:none;backdrop-filter:none}
  /* Dải dự án tràn hết hai mép: `.body` thụt vào var(--pad), kéo âm lại đúng
     bằng chừng đó là hết khung. Thẻ định vị theo left:50% nên vẫn nằm giữa,
     chỉ lộ thêm hai thẻ hai bên — rộng rãi hơn hẳn khung cũ. */.ghp .projsec{margin-left:calc(var(--pad) * -1);margin-right:calc(var(--pad) * -1)}.ghp .projsec .kicker, .ghp .projsec h2, .ghp .projsec .lead{padding-left:var(--pad);padding-right:var(--pad)}
  /* Tràn mép rồi thì hàng điều hướng chạm tới cụm nút nổi (rộng 46 + cách mép
     12) — chừa đúng chừng đó bên phải để nút ❯ không nằm dưới bong bóng. */.ghp .projsec .cf-nav{padding-left:var(--pad);padding-right:66px}.ghp .cf-cap .n{overflow:hidden;text-overflow:ellipsis}
  /* Điện thoại: rút quãng cuộn của dải ảnh lại — 460vh trên màn dọc là khách
     phải vuốt gần chục lần mới qua được một khối. */.ghp .unf{--unf-run:420vh}.ghp .unf-col{width:34vw;min-width:132px}
  /* Màn hẹp: khoảng trắng bên trái ít, thu chữ lại và bớt thụt dòng dưới cho
     hai chữ không chạm mép dải ảnh. */.ghp .unf-word{font-size:clamp(26px,7.4vw,40px)}.ghp .unf-word.r{margin-left:.7em}.ghp .cf{height:400px}.ghp .cfi{width:214px;height:300px;margin:-150px 0 0 -107px}.ghp .searchbox input{width:130px}
}
</select></select></button>
</style>
{/literal}
<div class="ghp">
<!-- ================= HEADER ================= -->
<header class="head" data-gh-section="profile" data-gh-label="Thẻ giới thiệu & ảnh bìa">
  <div class="headcard">
    <div class="cover" data-fbg="cover.image" data-fvideo="cover">
      <span class="veil"></span>

      <div class="coverblock">
        <div class="eb" data-f="hero.eyebrow"></div>
        <h2 data-fhtml="hero.title"></h2>
        <div class="coverbtns" data-flist="hero.buttons">
          <a class="cb solid" href="#duan"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"></path><path d="M4 9h16M9 9v11"></path></svg> Xem 6 dự án đang bán</a>
          <a class="cb line" href="#dangky"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18v12H3z"></path><path d="M3 7l9 6 9-6"></path></svg> Nhận bảng hàng &amp; giá</a>
        </div>
      </div>
    </div>

    <div class="idrow">
      <div class="ava">
        <div class="avatar" data-f="profile.initials" data-fbg="profile.avatar"></div>
        <span class="live"></span>
      </div>
      <div class="idmain">
        <h1 class="grad" data-f="profile.name"></h1>
        <div class="meta">
          <i><span class="mi g"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M8.3 12.3l2.5 2.5 4.9-5.1"></path></svg></span><span class="on" data-f="profile.status" data-fwrap="i"></span></i>
          <i><span class="mi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V9.5l8-5.5 8 5.5V20z"></path><path d="M9.6 20v-5.2h4.8V20"></path></svg></span> <span data-f="profile.role"></span></i>
          <i><span class="mi b"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.4"></circle></svg></span> <span data-f="profile.area" data-fwrap="i"></span></i>
        </div>
        <div class="chips" data-flist="profile.chips" style="margin-top:7px"><span class="chip plain"><span class="cic"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3c2.8 3 2.8 15 0 18M12 3c-2.8 3-2.8 15 0 18"></path></svg></span>g-hub.vn/info/binhtv</span></div>
      </div>

      <div class="idacts" data-flist="profile.actions">
        <a class="ib co" href="tel:0904569888" aria-label="Gọi 0904 569 888"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"></path></svg><span class="tip">Gọi 0904 569 888</span></a>
        <a class="ib bl" href="#" aria-label="Nhắn Zalo"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 5h16v11H9l-5 4z"></path></svg><span class="tip">Nhắn Zalo</span></a>
        <a class="ib mi" href="#duan" aria-label="Xem dự án"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 4h16v16H4z"></path><path d="M4 9h16M9 9v11"></path></svg><span class="tip">6 dự án đang bán</span></a>
        <a class="ib vi" href="#video" aria-label="Video dự án"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 6h11v12H4z"></path><path d="M15 10l5-3v10l-5-3z"></path></svg><span class="tip">Video dự án</span></a>
        <a class="ib" href="#ketnoi" aria-label="Lưu danh thiếp"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 4v12M7 11l5 5 5-5M5 20h14"></path></svg><span class="tip">Lưu danh thiếp</span></a>
        <a class="ib main" href="#dangky" aria-label="Đặt lịch tư vấn"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16v14H4z"></path><path d="M4 10h16M9 3v4M15 3v4"></path></svg><span class="tip">Đặt lịch tư vấn 15 phút</span></a>
      </div>
    </div>
  </div>
</header>

<!-- ================= MENU ================= -->
<nav class="tabs">
  <div class="tabsin">
    <div class="tabshell">
      <div class="tabbar">
        <div class="pillbar" data-flist="nav">
          <a class="tab on" href="#gioithieu"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path></svg> Giới thiệu</a>
          <a class="tab" href="#video"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 6h11v12H4z"></path><path d="M15 10l5-3v10l-5-3z"></path></svg> Video</a>
          <a class="tab" href="#duan"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 4h16v16H4z"></path><path d="M4 9h16M9 9v11"></path></svg> Dự án</a>
          <a class="tab" href="#nhatky"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 8h3l1.5-2h7L17 8h3v11H4z"></path><circle cx="12" cy="13" r="3.4"></circle></svg> Nhật ký khách hàng</a>
          <a class="tab" href="#quandiem"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h14v16l-7-3.6L5 20z"></path></svg> Quan điểm</a>
        </div>
      </div>
      <a class="b b-primary b-sm" href="#dangky">Nhận bảng hàng</a>
    </div>
  </div>
</nav>

<!-- ================= THÂN ================= -->
<div class="body">

  <!-- GIỚI THIỆU -->
  <section class="card rv" id="gioithieu" data-gh-section="intro" data-gh-label="Giới thiệu">
    <div class="card-h"><span class="ht"><span class="hi co"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path></svg></span><h2 class="grad">Giới thiệu</h2></span><span class="more">Galaxy Group</span></div>
    <div class="introwrap">
      <div class="introL">
        <!-- Đoạn giới thiệu LUÔN do render service cấp (tự soạn, hoặc câu mặc
             định dựng theo tên + chức danh của chính chủ trang). Không để chữ
             mẫu ở đây: trang nào chưa sửa sẽ xưng tên người seed. -->
        <p class="bio" data-f="intro.bio"></p>
        <div class="inforows" data-flist="intro.facts">
          <div class="inforow"><span class="ic"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 20V9l8-5 8 5v11z"></path></svg></span><span><small>Vị trí công tác</small><b>Chuyên gia tư vấn BĐS cao cấp</b></span></div>
          <div class="inforow"><span class="ic"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.3"></circle></svg></span><span><small>Khu vực phụ trách</small><b>Hà Nội · Hưng Yên</b></span></div>
          <div class="inforow"><span class="ic"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"></path></svg></span><span><small>Điện thoại · Zalo</small><b>0904 569 888</b></span></div>
          <div class="inforow"><span class="ic"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 6h18v12H3z"></path><path d="M3 7l9 6 9-6"></path></svg></span><span><small>Email công việc</small><b>info.binhtv@gmail.com</b></span></div>
        </div>
        <div class="chips" data-flist="intro.tags">
          <span class="chip br">Căn hộ cao cấp</span><span class="chip bl">Thấp tầng &amp; shophouse</span>
          <span class="chip ok">Hồ sơ vay ngân hàng</span><span class="chip wr">Đầu tư cho thuê</span>
          <span class="chip">Khách ở nước ngoài</span>
        </div>
      </div>
      <div class="pledgebox" data-gh-section="pledge" data-gh-label="Cam kết với khách hàng"><h4 data-f="pledge.title"></h4><div class="pgrid" data-flist="pledge.items">
        <h4 data-f="pledge.title"></h4>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span><span><b>Phản hồi đúng lúc</b> — Luôn có mặt khi bạn cần trong giờ làm việc.</span></div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span><span><b>Tư vấn đúng nhu cầu</b> — Không ép mua, không bán bằng mọi giá.</span></div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span><span><b>Giá đúng chủ đầu tư</b> — Minh bạch, không thu phí môi giới.</span></div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span><span><b>Thông tin trung thực</b> — Đủ ưu điểm, rõ cả những điều cần lưu ý.</span></div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span><span><b>Bảo mật tuyệt đối</b> — Không chia sẻ thông tin của bạn cho bên thứ ba.</span></div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span><span><b>Đồng hành đến cùng</b> — Từ lúc tìm hiểu, ký hợp đồng đến nhận nhà và sau bán hàng.</span></div>
      </div>
      </div>
    </div>
  </section>

  <!-- Ô NHẮN NHANH -->
  <section class="card rv" data-gh-section="composer" data-gh-label="Ô nhắn nhanh">
    <div class="composer">
      <div class="av" data-f="profile.initials" data-fbg="profile.avatar">TB</div>
      <a class="fake" href="#dangky" data-f="composer.placeholder"></a>
    </div>
    <div class="composer-acts" data-flist="composer.actions">
      <button type="button" onclick="location.hash='#dangky'"><svg class="c1" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 4h16v16H4z"></path><path d="M4 9h16"></path></svg> Nhận bảng hàng</button>
      <button type="button" onclick="location.hash='#dangky'"><svg class="c2" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 6h16v14H4z"></path><path d="M4 10h16M9 3v4M15 3v4"></path></svg> Đặt lịch xem nhà</button>
      <button type="button" onclick="location.hash='#video'"><svg class="c3" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 6h11v12H4z"></path><path d="M15 10l5-3v10l-5-3z"></path></svg> Xem video dự án</button>
    </div>
  </section>

  <!-- VIDEO -->
  <section class="card rv" id="video" data-gh-section="video" data-gh-label="Video nổi bật">
    <div class="card-h"><span class="ht"><span class="hi vi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 6h11v12H4z"></path><path d="M15 10l5-3v10l-5-3z"></path></svg></span><h2 class="grad" data-f="video.title"></h2></span><span class="more" id="vcount">6 video</span></div>
    <p class="card-sub" data-f="video.lead"></p>
    <!-- Video nổi bật CHIA ĐÔI: ảnh trái · chữ phải (xem CSS "VIDEO NỔI BẬT —
         CHIA ĐÔI"). Chú thích nay nằm TRONG khối này chứ không còn là thẻ <p>
         riêng bên dưới — id/khoá data-fhtml giữ nguyên nên sửa tại chỗ và bộ
         nạp dữ liệu không phải đổi gì. -->
    <div class="vidmain" id="vheromain">
      <figure class="vid" id="vhero"><span class="tg">Mới nhất</span><button class="play" aria-label="Phát video"><svg viewbox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg></button><span class="dur">03:24</span></figure>
      <div class="vheroside">
        <span class="vheroproj" id="vheroproj" hidden=""><i></i><span class="nm"></span></span>
        <p class="vidcap" id="vherocap" data-fhtml="video.heroCaption">Đi thực tế The Magnolia — MIK Group, phường Bồ Đề, Hà Nội<span>Bàn giao dự kiến Quý III/2028 · pháp lý sở hữu lâu dài</span></p>
      </div>
    </div>
    <!-- Dải video CHẠY NGANG THEO CUỘN — cùng cơ chế với khối "Quan điểm"
         (xem ghPinRail() ở cuối file): cuộn chuột / vuốt bao nhiêu thì thẻ đi
         ngang bấy nhiêu, hết thẻ cuối mới nhả trang. Thay hẳn nút "Xem thêm
         video" cũ (anh Bình chốt 17/08) — nay mọi video đều nằm trên dải.

         HÀNG CHIP DỰ ÁN NẰM TRONG lớp dính (anh Bình chốt 17/08): lúc dải đang
         trượt ngang, người xem phải luôn nhìn thấy còn những dự án nào và mình
         đang trôi tới dự án nào — để chip ở ngoài thì nó cuộn mất khỏi màn hình
         ngay nhịp đầu tiên. Chip của dự án đang dẫn đầu khung được đánh dấu
         `.now` (xem markCurrentProject()). -->
    <div class="pinrail vrail">
      <div class="pinrail-in">
        <div class="vfilter">
          <div class="vchips" id="vchips"></div>
          <span class="vsearch"><input id="vsearch" placeholder="Tìm video..."><span><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-4-4"></path></svg></span></span>
        </div>
        <div class="vgrid" id="vgrid"></div>
        <div class="pinbar"><i></i></div>
      </div>
    </div>
    <!-- Chồng ảnh video — chỉ dựng trên điện thoại, xem khối CSS "CHỒNG ẢNH VIDEO".
         Khung (.vdframe) đứng yên, JS chỉ đổi CHỮ bên trong; chỉ #vdstage chứa chồng ảnh động. -->
    <div class="vdeck" id="vdeck">
      <div class="vdframe">
        <header class="vdh"><b id="vdtitle"></b><div class="vdmeta"><span class="vdtag"><i></i><span class="nm" id="vdproj"></span></span><span class="vddur" id="vddur"></span></div></header>
        <div class="vdstage" id="vdstage"></div>
        <footer class="vdf"><button type="button" class="go"><svg viewbox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg>Xem video</button><span class="by" id="vdby"></span></footer>
      </div>
      <div class="vddots" id="vddots"></div>
      <p class="vdhint" id="vdhint">Kéo ảnh lên để xem video sau · vuốt ngang để đổi dự án</p>
    </div>
  </section>

  <!-- DỰ ÁN — giao diện Sổ tay -->
  <section class="projsec rv" id="duan" data-gh-section="projects" data-gh-label="Dự án đang bán">
    <div class="kicker">Thông tin dự án</div>
    <h2 data-fhtml="projects.title"></h2>
    <p class="lead" data-f="projects.lead"></p>
    <div class="cf-wrap">
      <div class="cf-stage" id="cfstage"></div>
      <div class="cf-nav">
        <button class="cf-btn cfprev" aria-label="Trước">❮</button>
        <div class="cf-cap">
          <div class="n" id="cfname">The Magnolia</div>
          <div class="r">Bấm thẻ giữa để xem chi tiết</div>
        </div>
        <button class="cf-btn cfnext" aria-label="Sau">❯</button>
      </div>
      <div class="cf-dots" id="cfdots"></div>
    </div>
  </section>

  <!-- NHẬT KÝ KHÁCH HÀNG -->
  <section class="starsec rv" id="nhatky" data-gh-section="diary" data-gh-label="Nhật ký khách hàng">
    <div class="starhead">
      <div class="kicker" data-f="diary.kicker"></div>
      <h2 data-fhtml="diary.title"></h2>
      <p data-f="diary.lead"></p>
    </div>
    <!-- Dải ảnh mở ra theo cuộn — thẻ ảnh do JS đổ vào 4 cột (xem unfurl() cuối
         trang). Giữ nguyên id: JS bám theo id, đổi tên là khối đứng im. -->
    <div class="unf" id="unfwrap">
      <div class="unf-pin">
        <div class="unf-banner" id="unfbanner">
          <!-- Hai chữ nằm TRƯỚC .unf-scene: xếp lớp dưới dải ảnh nên ảnh phóng
               to tới đâu là phủ dần lên chữ tới đó, cộng thêm một nhịp mờ đi cho
               mượt. Đưa lên sau .unf-scene là chữ đè lên ảnh, hỏng ý đồ. -->
          <div class="unf-words">
            <div class="unf-word l" id="unfword1">Khoảnh khắc</div>
            <div class="unf-word r" id="unfword2">Đồng hành</div>
          </div>
          <div class="unf-scene">
            <div class="unf-veil"></div>
            <div class="unf-veil x"></div>
            <div class="unf-mat" id="unfmat">
              <div class="unf-col"></div>
              <div class="unf-col"></div>
              <div class="unf-col"></div>
              <div class="unf-col"></div>
            </div>
          </div>
          <!-- Hai dải phản chiếu mặt kính: nằm SAU .unf-scene nên phủ lên ảnh. -->
          <div class="unf-gloss t"></div>
          <div class="unf-gloss b"></div>
        </div>
      </div>
    </div>
    <div class="starfoot">
      <p class="starline" data-fhtml="diary.line">Mỗi quyết định đúng đều bắt đầu từ <b>một cuộc trao đổi.</b></p>
      <a class="b b-primary b-lg" href="#dangky" data-f="diary.cta"></a>
    </div>
  </section>

  <!-- QUAN ĐIỂM -->
  <section class="creedsec rv" id="quandiem" data-gh-section="creed" data-gh-label="Quan điểm làm nghề">
    <div class="eyebrow" data-f="creed.eyebrow"></div>
    <h2 data-fhtml="creed.title"></h2>
    <img class="creedbird" src="/landing-assets/personal/img/creed-bird.png" alt="" aria-hidden="true">
    <p class="intro" data-f="creed.lead">Bốn nguyên tắc tôi giữ trong mọi giao dịch — kể cả khi nó khiến tôi mất một hợp đồng trước mắt. Đây là thứ khách cũ nhớ về tôi và giới thiệu tôi cho người quen của họ.</p>
    <!-- Dải thẻ chạy ngang theo cuộn — xem ghPinRail() ở cuối file. Bọc hai
         lớp: .pinrail giữ chỗ cao bằng quãng đường ngang, .pinrail-in là lớp
         dính lại giữa khung nhìn. -->
    <div class="pinrail">
      <div class="pinrail-in">
        <div class="creedgrid" data-flist="creed.items"></div>
        <div class="pinbar"><i></i></div>
      </div>
    </div>
    <p class="creedquote" data-fhtml="creed.quote"></p>
  </section>

  <!-- QUY TRÌNH -->
  <section class="card rv" id="quytrinh" data-gh-section="process" data-gh-label="Quy trình đồng hành">
    <!-- Tiêu đề nhận HTML (data-fhtml): vế trong <em> xuống DÒNG RIÊNG và phủ
     gradient, y như tiêu đề khối Quan điểm · Dự án · Nhật ký. -->
    <div class="card-h"><span class="ht"><span class="hi mi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 12h16M14 6l6 6-6 6"></path></svg></span><h2 class="grad" data-fhtml="process.title"></h2></span></div>
    <!-- Các bước do bộ nạp đổ vào từ process.steps (defaults.json hoặc nội
         dung riêng của từng người) — xem khuôn TPL['process.steps'] ở cuối file. -->
    <p class="steplead" data-f="process.lead"></p>
    <div class="pinrail">
      <div class="pinrail-in">
        <div class="steps" data-flist="process.steps"></div>
        <div class="pinbar"><i></i></div>
      </div>
    </div>
    <p class="stepnote" data-fhtml="process.note"></p>
  </section>

  <!-- FORM -->
  <section class="leadsec rv" id="dangky" data-gh-section="form" data-gh-label="Form nhận thông tin">
    <div class="leadL">
      <div class="eb" data-f="form.eyebrow"></div>
      <h2 data-f="form.title"></h2>
      <p class="who" data-fhtml="form.who"></p>
      <div class="benefits" data-flist="form.benefits">
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span> Dự án chọn lọc, pháp lý rõ ràng</div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span> Giá bán theo đúng chính sách chủ đầu tư</div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span> Tư vấn 1–1 theo nhu cầu thực tế</div>
        <div><span class="ck"><svg viewbox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"></path></svg></span> Không spam, không sale làm phiền</div>
      </div>
      <p class="sign" data-f="form.sign"></p>
    </div>

    <form class="leadR" novalidate="">
      <p class="top" data-f="form.intro">Để lại thông tin, tôi sẽ gọi lại tư vấn chi tiết trong thời gian sớm nhất — kèm bảng hàng và giá thực trả của dự án bạn quan tâm.</p>
      <div class="fgrid">
        <div class="fld">
          <span class="fi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.6"></circle><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6"></path></svg></span>
          <input placeholder="Họ và tên *" autocomplete="name" aria-label="Họ và tên">
        </div>
        <div class="fld">
          <span class="fi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"></path></svg></span>
          <input placeholder="Số điện thoại *" inputmode="tel" autocomplete="tel" aria-label="Số điện thoại">
        </div>
        <div class="fld">
          <span class="fi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h18v12H3z"></path><path d="M3 7l9 6 9-6"></path></svg></span>
          <input placeholder="Email" inputmode="email" autocomplete="email" aria-label="Email">
        </div>
        <div class="fld">
          <span class="fi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.3"></circle></svg></span>
          <select id="fproject" aria-label="Dự án quan tâm"><option>-- Dự án quan tâm --</option></select>
          <span class="ar"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"></path></svg></span>
        </div>
      </div>
      <div class="needs" role="group" aria-label="Nhu cầu" data-flist="form.needs">
        <button type="button" class="need" aria-pressed="true">Để ở</button>
        <button type="button" class="need" aria-pressed="false">Đầu tư</button>
        <button type="button" class="need" aria-pressed="false">Cho thuê</button>
        <button type="button" class="need" aria-pressed="false">Cần vay ngân hàng</button>
        <button type="button" class="need" aria-pressed="false">Dưới 4 tỷ</button>
        <button type="button" class="need" aria-pressed="false">4 – 8 tỷ</button>
        <button type="button" class="need" aria-pressed="false">Trên 8 tỷ</button>
      </div>
      <button class="bigsend" type="submit" data-f="form.submit" data-fkeep="1">Gửi thông tin <svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 12h15M13 6l6 6-6 6"></path></svg></button>
      <div class="pledge" data-flist="form.pledge" data-fkeep="1">
        <!-- .hd là phần data-fkeep giữ lại khi đổ danh sách; nhãn nằm trong
             <span> riêng vì bộ nạp data-fkeep chỉ ghi được vào NODE ĐẦU TIÊN,
             mà node đầu ở đây là thẻ <svg>. -->
        <div class="hd"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 3v6c0 5-3.4 8.2-8 9-4.6-.8-8-4-8-9V6z"></path></svg> <span data-f="form.pledgeTitle">Cam kết với khách hàng</span></div>
        <div class="it"><span class="pi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg></span> Liên hệ trong giờ làm việc, gọi đúng một lần</div>
        <div class="it"><span class="pi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="10" rx="2"></rect><path d="M8 10V7a4 4 0 018 0v3"></path></svg></span> Bảo mật thông tin khách hàng</div>
        <div class="it"><span class="pi"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v11H9l-5 4z"></path></svg></span> Tư vấn miễn phí, không phí môi giới</div>
      </div>
      <p class="fine">Bạn có thể yêu cầu xoá thông tin bất cứ lúc nào.</p>
    </form>
  </section>

  <!-- DẢI CTA CUỐI TRANG -->
  <section class="ctaband rv" id="ketnoi" data-gh-section="cta" data-gh-label="Dải kêu gọi cuối trang">
    <h2 data-fhtml="cta.title">Galaxy Group – Hệ sinh thái bất động sản thịnh vượng
      <span class="hl">Đồng hành kiến tạo giá trị bền vững</span>
    </h2>
    <p data-f="cta.lead">Từ tư vấn, đầu tư, giao dịch đến quản lý và khai thác tài sản, chúng tôi đồng hành trên từng chặng đường, mang đến những giải pháp minh bạch, hiệu quả và giá trị lâu dài.</p>
    <div class="acts" data-flist="cta.buttons">
      <a class="cta1" href="#dangky">Đặt lịch tư vấn <svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 12h15M13 6l6 6-6 6"></path></svg></a>
      <a class="cta2" href="#gioithieu">Xem câu chuyện của tôi <svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 12h15M13 6l6 6-6 6"></path></svg></a>
    </div>
  </section>
</div>

<footer class="foot" data-gh-section="footer" data-gh-label="Chân trang">
  <div class="footin" data-flist="footer.columns">
    <div>
      <div class="fbrand"><span class="m">TB</span><span><b>Trương Văn Bình</b><small>binhtv.g-hub.vn</small></span></div>
      <p>Chuyên gia tư vấn bất động sản cao cấp — Galaxy Group. Đồng hành cùng khách mua để ở, đầu tư cho thuê và chuyển nhượng tại Hà Nội &amp; Hưng Yên.</p>
      <div class="fhot">Hotline: 0904 569 888</div>
      <p style="margin-top:4px">info.binhtv@gmail.com</p>
    </div>
    <div>
      <h4>Dự án đang phân phối</h4>
      <div class="flist" id="footproj"></div>
    </div>
    <div>
      <h4>Trên trang này</h4>
      <div class="flist">
        <a href="#gioithieu"><i></i>Giới thiệu</a>
        <a href="#video"><i></i>Video nổi bật</a>
        <a href="#nhatky"><i></i>Nhật ký khách hàng</a>
        <a href="#quandiem"><i></i>Quan điểm làm nghề</a>
        <a href="#dangky"><i></i>Nhận bảng hàng &amp; giá</a>
      </div>
    </div>
  </div>
  <div class="footbot" data-f="footer.copyright">© 2026 Trương Văn Bình · Galaxy Group — binhtv.g-hub.vn</div>
</footer>

<div class="bubbles">
  <a class="bub" data-fhref="contact.telHref" href="#dangky" aria-label="Gọi điện"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"></path></svg></a>
  <a class="bub" data-fhref="contact.zaloHref" href="#dangky" aria-label="Nhắn Zalo"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 5h16v11H9l-5 4z"></path></svg></a>
  <a class="bub main" href="#dangky" aria-label="Nhận bảng hàng"><svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16v13H4z"></path><path d="M4 10h16M9 3v4M15 3v4"></path></svg></a>
</div>

<!-- Thiệp mời liên hệ: hiện sau 5 giây. Đóng một lần thì cả phiên không hiện
     lại nữa — mời chào hai lần trong một lượt xem là làm phiền. -->
<div class="hail" id="hail" role="dialog" aria-modal="true" aria-label="Kết nối với tôi">
 <div class="hcard">
  <button class="x" type="button" id="hailx" aria-label="Đóng">×</button>
  <div class="who">
    <span class="av" data-f="profile.initials" data-fbg="profile.avatar"></span>
    <span><b data-f="profile.name"></b><small>Kết nối với tôi — Cùng tìm đúng tài sản, đúng thời điểm, đúng giá trị.</small></span>
  </div>
  <a class="call" data-fhref="contact.telHref" href="#dangky">
    <svg viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"></path></svg>
    Gọi ngay <span data-f="contact.phoneText"></span>
  </a>
  <div class="or">hoặc để lại số</div>
  <form id="hailform" novalidate="">
    <input id="hailname" placeholder="Tên của bạn" autocomplete="name" aria-label="Tên của bạn">
    <input id="hailphone" placeholder="Số điện thoại" inputmode="tel" autocomplete="tel" aria-label="Số điện thoại">
    <button type="submit">Kết nối với tôi</button>
  </form>
 </div>
</div>
</div>
{literal}
<script id="gh-site-data" type="application/json">
{"seo":{"title":"Giám đốc kinh doanh - Galaxy Group","description":"Tôi là Trịnh Tuấn Hưng – Giám đốc Kinh doanh của Galaxy Group &amp; Giám đốc dự án Masterise Homes, tôi trực tiếp dẫn dắt đội ngũ kinh doanh và đồng hành cùng khách hàng trong từng quyết định đầu tư. Với tầm nhìn thị trường, sự am hiểu bất động sản và chữ Tín đặt lên hàng đầu. Tôi cam kết mang đến những sản phẩm phù hợp, thông tin minh bạch và giá trị đầu tư bền vững.","ogImage":""},"assets":{"leadUrl":"https://server.g-hub.vn/public/nguoi/trinh-tuan-hung/lien-he"},"cover":{"image":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/29c62bc3fc2dbd0d-mr-tr-nh-tu-n-h-ng.jpg","video":"","posterImage":"","focal":{"desktop":"50% 50%","tablet":"50% 50%","mobile":"50% 50%"}},"hero":{"eyebrow":"HỆ SINH THÁI BẤT ĐỘNG SẢN GALAXY GROUP","title":"Chọn đúng căn, đúng giá — \u003cem>đúng thời điểm\u003c/em>","buttons":[{"label":"Xem dự án đang bán","href":"#duan","icon":"grid"},{"label":"Nhận bảng hàng & giá","href":"#dangky","icon":"mail"}]},"intro":{"bio":"Tôi là Trịnh Tuấn Hưng, Giám đốc kinh doanh tại Galaxy Group. Tôi đồng hành cùng khách hàng từ lúc tìm hiểu dự án đến khi nhận bàn giao: đi hiện trường, đối chiếu giá thực trả, phân tích pháp lý và dòng tiền trước khi bàn tới chuyện ký hợp đồng.","facts":[{"icon":"home","label":"Vị trí công tác","value":"Giám đốc kinh doanh","bind":"position","_i":0},{"icon":"phone","label":"Điện thoại · Zalo","value":"0981 556 456","bind":"phone","_i":1},{"icon":"mail","label":"Email công việc","value":"trinhhungrubik@gmail.com","bind":"email","_i":2}],"tags":[]},"pledge":{"title":"Niềm tin của Quý khách là trách nhiệm của tôi","items":[{"title":"Phản hồi đúng lúc","text":"Luôn có mặt khi bạn cần trong giờ làm việc."},{"title":"Tư vấn đúng nhu cầu","text":"Không ép mua, không bán bằng mọi giá."},{"title":"Giá đúng chủ đầu tư","text":"Minh bạch, không thu phí môi giới."},{"title":"Thông tin trung thực","text":"Đủ ưu điểm, rõ cả những điều cần lưu ý."},{"title":"Bảo mật tuyệt đối","text":"Không chia sẻ thông tin của bạn cho bên thứ ba."},{"title":"Đồng hành đến cùng","text":"Từ lúc tìm hiểu, ký hợp đồng đến nhận nhà và sau bán hàng."}]},"composer":{"placeholder":"Bạn đang tìm căn thế nào? Nhắn tôi…","actions":[{"label":"Nhận bảng hàng","href":"#dangky","icon":"grid"},{"label":"Đặt lịch xem nhà","href":"#dangky","icon":"cal"},{"label":"Xem video dự án","href":"#video","icon":"video"}]},"video":{"title":"Video nổi bật","lead":"HANOI SEASONS GARDEN - PHÂN KHU THE BLOOM: CHẤT SỐNG DUY MỸ GIỮA MIỀN NỞ HOA","hero":{"img":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/673401146bf41fe2--nh-facade-d-n.jpg","dur":"","url":"","proj":""},"heroCaption":"","items":[{"proj":"HaNoi Season Garden","title":"HANOI SEASONS GARDEN - PHÂN KHU THE BLOOM: CHẤT SỐNG DUY MỸ GIỮA MIỀN NỞ HOA","sub":"","dur":"01:47","img":"","url":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/5a4569be8d7a4843-snapsave-vn_facebook_6a850fb915c9b.mp4"},{"proj":"LUMIÈRE ESSENCE PEAK","title":"LUMIÈRE Essence Peak - NHẬT KÝ HẠNH PHÚC TẠI NƠI TA GỌI LÀ NHÀ","sub":"","dur":"00:46","img":"","url":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/dbdf974cede8f5e4-snapsave-vn_facebook_6a85113592d3d.mp4"},{"proj":"HaNoi Season Garden","title":"HaNoi Seasons Garden - Tiến độ tháng 7","sub":"","dur":"01:01","img":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/51395e6157bb98ad-1786329245756_1698717415941713246_g7172501188112139076_3b4b6.jpg","url":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/e1523a948ec56647-hgs-t7-final-1-.mp4"},{"proj":"Lumière Ocean Crest","title":"TRỌN VẸN HÀNH TRÌNH KIẾN TẠO CỦA MASTERISE HOMES TẠI OCEAN CITY","sub":"","dur":"04:02","img":"","url":"https://cdn.g-hub.vn/uploads/personal-site/6a0c9457bd110990941f029f/2026/08/b20221d7a91a5775-fsave-com_reels_tron-ven-hanh-trinh-kien-tao-cua-masteri_med.mp4"}]},"projects":{"title":"DỰ ÁN \u003cem>ĐANG TRIỂN KHAI\u003c/em>","lead":"Các dự án tôi đang trực tiếp phân phối — bấm vào thẻ để xem chi tiết dự án.","items":[{"n":"HaNoi Season Garden","slug":"hanoi-season-garden","dev":"","typeLabel":"Căn hộ","addr":"Cao Xà Lá","legal":"Lâu dài","hand":"","desc":"HANOI SEASONS GARDEN là tổ hợp căn hộ hạng sang của Masterise Homes tọa lạc tại số 233–235 Nguyễn Trãi, trung tâm quận Thanh Xuân, Hà Nội. Dự án sở hữu quỹ đất hơn 8,2ha hiếm có trong nội đô, gồm 10 tòa tháp cao 35–46 tầng với khoảng 4.000 căn hộ cao cấp thuộc hai dòng sản phẩm Masteri và Lumière.","img":"https://cdn.g-hub.vn/uploads/inventory/projects/69f254b21886878e4d0a0038/2026/06/0809109eacf47b0d-a-nh-pho-i-ca-nh-kie-n-tru-c-du-a-n.jpg","url":""},{"n":"LUMIÈRE ESSENCE PEAK","slug":"lumiere-essence-peak","dev":"","typeLabel":"Studio · 1PN · 1PN+ · 2PN · 2PN+ · 2PN Lift · 3PN · 3PN Lift · 4PN Lift · Duplex Lift · Penthouse · Penthouse Duplex","addr":"Global Gate, Hà Nội","legal":"Sở hữu lâu dài (Áp dụng đối với khách hàng quốc tịch Việt Nam)","hand":"Tue Oct 31 2028 00:00:00 GMT+0700 (Indochina Time)","desc":"Như tâm sen ngủ yên ngàn năm vẫn có thể vươn cao bừng nở, những giá trị chân thực từ tâm sẽ mạnh mẽ và vững bền bất kể thời gian.\nKhởi sinh từ tâm, Masterise Homes® kiến tạo dòng sản phẩm LUMIÈRE series với giá trị sống được chăm chút tỉ mỉ, sắp đặt với chiều sâu tinh tế. Ghi dấu ấn thứ 8 trong bộ sưu tập, LUMIÈRE Essence Peak thừa hưởng trọn vẹn những tâm huyết từ nhà phát triển, vươn lên từ tâm điểm đô thị, chạm đến tâm và tầm của chủ nhân tinh tuyển, trở thành biểu tượng cho giá trị bền vững tại Trung tâm quốc tế Global Gate, Hà Nội.","img":"https://cdn.g-hub.vn/uploads/inventory/projects/6a0c9423bd110990941efca5/2026/07/b0cb5fa87b3634dd-lumiere-essence-peak-co-loa-4.jpg","url":""},{"n":"Lumière Ocean Crest","slug":"lumiere-ocean-crest","dev":"","typeLabel":"Cao tầng","addr":"Ocean Park 2 – Ocean City","legal":"Lâu dài","hand":"Quý I.2028","desc":"LUMIÈRE OCEAN CREST – KIẾN TẠO CHUẨN SỐNG RESORT BÊN MẶT NƯỚC OCEAN CITY\n\nGiữa nhịp phát triển sôi động của Ocean City, Lumière Ocean Crest tiếp tục khẳng định dấu ấn của Masterise Homes với một không gian sống đẳng cấp, nơi kiến trúc hiện đại hòa quyện cùng thiên nhiên và hệ tiện ích chuẩn quốc tế.","img":"https://cdn.g-hub.vn/uploads/inventory/projects/69f254b21886878e4d0a0038/2026/07/3cde3f6d7e082ecf-v020_ct06_b1_overview_01-15k_update.jpg","url":""},{"n":"MASTERI ERA LANDMARK","slug":"masteri-era-landmark","dev":"","typeLabel":"Studio · 1PN+ · 2PN · 2PN+ 3PN · Penthouse","addr":"OCEAN PARK 3, OCEAN CITY","legal":"Sở hữu lâu dài","hand":"Dự kiến quý 1/2028","desc":"Giữa trái tim Ocean Park 3, Masteri Era Landmark vươn mình kiêu hãnh, mở ra một \"vùng đất diệu kỳ\" cho cộng đồng Masteri thời đại mới.\n\nNơi ánh sáng chạm nhẹ giữa tầng không, đánh thức một không gian sống ngập tràn sắc màu kỳ diệu.\n\nNơi thiên nhiên, con người và nhịp sống thời đại hoà quyện thành một không gian đồng điệu.\n\nNơi thế hệ trẻ mang theo giấc mơ riêng mình, sống trọn nhịp kết nối đa thế hệ bằng lòng biết ơn sâu sắc.","img":"https://cdn.g-hub.vn/uploads/inventory/projects/6a0c9423bd110990941efca5/2026/07/293bc667ab45f988-masteri-era-landmark.jpg","url":""},{"n":"Masteri Grand Coast","slug":"masteri-grand-coast","dev":"","typeLabel":"Studio · 1BR · 1BR+ · 2BR · 2BR+ · 3BR · 4BR · Duplex · Penthouse","addr":"Ocean Park 2, Ocean City","legal":"Sổ hồng lâu dài","hand":"Dự kiến quý I/2028","desc":"SỐNG PHONG THÁI TỰ DO","img":"https://cdn.g-hub.vn/uploads/inventory/projects/69f217e462b9ed099b9c2cc8/2026/05/b475b7cd705fc4ce-v15_ocp_exterior_09-1-scaled.webp","url":""}]},"diary":{"kicker":"Nhật ký khách hàng","title":"HÀNH TRÌNH ĐỒNG HÀNH \u003cem>CÙNG KHÁCH HÀNG\u003c/em>","lead":"Mỗi buổi đi xem nhà, mỗi lần ký hợp đồng và bàn giao đều được tôi lưu lại ở đây.","line":"Mỗi quyết định đúng đều bắt đầu từ \u003cb>một cuộc trao đổi.\u003c/b>","cta":"Đặt lịch tư vấn","items":[{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2Fe196089c9bfb15fa-620089379_1572825630598773_1354136025707543671_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F3401c85d3e5a6bfa-678900505_1640950953786240_3999831118250143210_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F7767615db69c6922-697183538_122169497126898670_2579615358336322698_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F3406db15a4216dcb-701561300_1658291285385540_1147825562421708919_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F37784339c5e484c3-705790418_1663998304814838_3670345307760778108_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F9fa3faf66723b499-706079870_1663998261481509_976279557420176794_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F9f9ac800ff3c5f13-717281752_1675029960378339_810783742029324540_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2Fc95300f61f6cd4b2-724039606_1680976109783724_3827345114739678546_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F3cf9abbeb692e424-723386474_1680976069783728_6678887437997294494_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F4cd2be5969f723cf-737819478_27748378861464318_1591615622313329834_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F73577162b5aebd24-744994453_1712037570010911_8630995298278680882_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F17274962f5210548-752652677_1712037343344267_6214722216480121233_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2F742ed489e6d3c42c-751339631_1712037293344272_6540600586219140676_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2Fc4d8dc82265fdf2d-619960307_1572825757265427_2427988277826545698_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2Ff8c92f85d55a5be8-623244913_1572826060598730_8672763321421866304_n.jpg&w=960"},{"img":"/landing-assets/anh?u=https%3A%2F%2Fcdn.g-hub.vn%2Fuploads%2Fpersonal-site%2F6a0c9457bd110990941f029f%2F2026%2F08%2Ff9691bb810b1eedc-622516667_1572825947265408_5496033688290902447_n.jpg&w=960"}]},"creed":{"eyebrow":"Quan điểm làm nghề","title":"ĐIỀU TÔI \u003cem>KHÔNG ĐÁNH ĐỔI\u003c/em>","lead":"Uy tín không được tạo nên từ số lượng giao dịch, mà từ những giá trị không bao giờ bị đánh đổi.","items":[{"icon":"star","title":"Trung thực","text":"Nói đúng sự thật. Sự thật quan trọng hơn một giao dịch."},{"icon":"heart","title":"Lợi ích khách hàng","text":"Đặt lợi ích khách hàng lên trước. Giải pháp phù hợp hơn doanh số."},{"icon":"shield","title":"Minh bạch","text":"Công khai mọi thông tin. Minh bạch từ đầu đến cuối."},{"icon":"users","title":"Đồng hành","text":"Đồng hành trước, trong và sau giao dịch. Theo khách hàng đến cùng."}],"quote":""},"process":{"title":"QUY TRÌNH \u003cem>ĐỒNG HÀNH CÙNG KHÁCH HÀNG\u003c/em>","lead":"5 bước đồng hành — 1 mục tiêu chung: giúp bạn ra quyết định bất động sản đúng và giữ được giá trị lâu dài.","steps":[{"img":"/landing-assets/personal/img/step-1.jpg","goalIcon":"star","title":"Lắng nghe & hiểu nhu cầu","paras":["Tôi lắng nghe để hiểu rõ mục tiêu, ngân sách, kế hoạch và kỳ vọng của bạn."],"goal":"Đề xuất sản phẩm phù hợp với nhu cầu thực tế."},{"img":"/landing-assets/personal/img/step-2.jpg","goalIcon":"clock","title":"Chọn lọc phương án tối ưu","paras":["Tôi phân tích thị trường và chọn lọc những phương án tốt nhất cho bạn."],"goal":"Tiết kiệm thời gian, chỉ tập trung vào cơ hội xứng đáng."},{"img":"/landing-assets/personal/img/step-3.jpg","goalIcon":"check","title":"Khảo sát & đánh giá thực tế","paras":["Đi thực tế dự án, kiểm tra pháp lý, tiện ích, tiềm năng và các yếu tố ảnh hưởng giá trị."],"goal":"Quyết định dựa trên thông tin minh bạch, đáng tin cậy."},{"img":"/landing-assets/personal/img/step-4.jpg","goalIcon":"home","title":"Hoàn thiện giao dịch & tối ưu quyền lợi","paras":["Tư vấn phương án tài chính, đàm phán và hoàn tất giao dịch an toàn, tối ưu cho bạn."],"goal":"Nhận đủ quyền lợi, tối ưu chi phí, mua được an tâm."},{"img":"/landing-assets/personal/img/step-5.jpg","goalIcon":"user","title":"Đồng hành sau giao dịch","paras":["Tiếp tục hỗ trợ sau giao dịch: tiến độ dự án, cho thuê, chuyển nhượng hoặc tối ưu giá trị tài sản."],"goal":"Đồng hành lâu dài, khai thác giá trị bền vững."}],"note":"Tôi không chỉ giúp bạn mua bất động sản, mà còn đồng hành để bạn sở hữu \u003cb>đúng giá trị\u003c/b> và \u003cb>an tâm lâu dài\u003c/b>."},"form":{"eyebrow":"Quan tâm","title":"DỰ ÁN?","who":"","benefits":["Dự án chọn lọc, pháp lý rõ ràng","Giá bán theo đúng chính sách chủ đầu tư","Tư vấn 1–1 theo nhu cầu thực tế","Không spam, không sale làm phiền"],"sign":"Uy tín tạo nên giá trị","intro":"Để lại thông tin, tôi sẽ gọi lại tư vấn chi tiết trong thời gian sớm nhất — kèm bảng hàng và giá thực trả của dự án bạn quan tâm.","needs":["Để ở","Đầu tư","Cần vay ngân hàng"],"submit":"Gửi thông tin","pledge":[{"icon":"check","text":"Tư vấn trung thực, đặt lợi ích khách hàng lên hàng đầu"},{"icon":"book","text":"Minh bạch tuyệt đối trong mọi thông tin"},{"icon":"lock","text":"Bảo mật thông tin khách hàng"},{"icon":"home","text":"Đồng hành trước, trong và sau giao dịch"}],"pledgeTitle":"Cam kết với khách hàng"},"cta":{"title":"Galaxy Group – Hệ sinh thái bất động sản thịnh vượng \u003cspan class=\"hl\">Đồng hành kiến tạo giá trị bền vững\u003c/span>","lead":"Từ tư vấn, đầu tư, giao dịch đến quản lý và khai thác tài sản, chúng tôi đồng hành trên từng chặng đường, mang đến những giải pháp minh bạch, hiệu quả và giá trị lâu dài.","buttons":[{"label":"Đặt lịch tư vấn","href":"#dangky"},{"label":"Xem câu chuyện của tôi","href":"#gioithieu"}]},"footer":{"columns":[{"type":"brand","initials":"TH","name":"Trịnh Tuấn Hưng","domain":"g-hub.vn/info/trinh-tuan-hung","desc":"","hotline":"Hotline: 0981 556 456","email":"trinhhungrubik@gmail.com","avatar":"https://g-hub.vn/sotay/cards/g10_trinh_tuan_hung.jpg"},{"title":"Trên trang này","links":[{"label":"Giới thiệu","href":"#gioithieu"},{"label":"Video nổi bật","href":"#video"},{"label":"Nhật ký khách hàng","href":"#nhatky"},{"label":"Quan điểm làm nghề","href":"#quandiem"},{"label":"Nhận bảng hàng & giá","href":"#dangky"}]}],"copyright":"© 2026 Trịnh Tuấn Hưng · Galaxy Group — g-hub.vn/info/trinh-tuan-hung"},"nav":[{"label":"Giới thiệu","href":"#gioithieu","icon":"user"},{"label":"Video","href":"#video","icon":"video"},{"label":"Dự án","href":"#duan","icon":"grid"},{"label":"Nhật ký khách hàng","href":"#nhatky","icon":"camera"},{"label":"Quan điểm","href":"#quandiem","icon":"book"}],"profile":{"status":"Đang nhận tư vấn hôm nay","area":"","chips":[{"text":"g-hub.vn/info/trinh-tuan-hung","tone":"plain","icon":"web","bind":"url"}],"actions":[{"label":"Gọi 0981 556 456","href":"tel:0981556456","icon":"phone","tone":"co","bind":"tel","_i":0},{"label":"Nhắn Zalo","href":"https://zalo.me/0981556456","icon":"chat","tone":"bl","bind":"zalo","_i":1},{"label":"Dự án đang bán","href":"#duan","icon":"grid","tone":"mi","_i":2},{"label":"Video dự án","href":"#video","icon":"video","tone":"vi","_i":3},{"label":"Lưu danh thiếp","href":"#ketnoi","icon":"down","_i":4},{"label":"Đặt lịch tư vấn 15 phút","href":"#dangky","icon":"cal","primary":true,"_i":5}],"name":"Trịnh Tuấn Hưng","initials":"TH","avatar":"https://g-hub.vn/sotay/cards/g10_trinh_tuan_hung.jpg","refId":"trinh-tuan-hung","shareRef":"6a0c9457bd110990941f029f","role":"Giám đốc kinh doanh · Galaxy Group"},"contact":{"telHref":"tel:0981556456","zaloHref":"https://zalo.me/0981556456","mailHref":"mailto:trinhhungrubik@gmail.com","facebookHref":"","phoneText":"0981 556 456","email":"trinhhungrubik@gmail.com"}}
</script>
{/literal}
{literal}
<script>

/* ============================================================
 * gh-personal-landing — bộ nạp dữ liệu cá nhân hoá
 * Đọc JSON trong #gh-site-data rồi đổ vào các nút có data-f / data-fhtml /
 * data-fbg / data-flist. Builder chỉ cần sửa JSON, không đụng vào HTML.
 * ============================================================ */
window.SITE = (function () {
  try { return JSON.parse(document.getElementById('gh-site-data').textContent || '{}'); }
  catch (e) { return {}; }
})();

(function () {
  var S = window.SITE;
  var get = function (path) {
    return path.split('.').reduce(function (o, k) { return o == null ? o : o[k]; }, S);
  };
  var esc = function (t) { return String(t == null ? '' : t); };

  /* --- chữ / HTML / ảnh nền --- */
  document.querySelectorAll('[data-f]').forEach(function (el) {
    var v = get(el.getAttribute('data-f'));
    if (v == null || v === '') {
      /* Thiếu dữ liệu mà vẫn giữ khung thì trên trang còn trơ lại cái icon với
         một dòng trống. `data-fwrap="<selector>"` = ẩn luôn cả dòng đó. */
      var wrap = el.getAttribute('data-fwrap');
      if (wrap) { var host = el.closest(wrap); if (host) host.style.display = 'none'; return; }
      /* Thiếu dữ liệu mà GIỮ chữ trong mẫu là trang của người này hiện nội dung
         của người seed (tên, dự án, chú thích video) — phải dọn sạch. fkeep chỉ
         xoá đúng nút chữ đầu, giữ icon/thẻ con bên trong. */
      if (el.hasAttribute('data-fkeep')) { if (el.childNodes[0]) el.childNodes[0].nodeValue = ''; }
      else el.textContent = '';
      return;
    }
    if (el.hasAttribute('data-fkeep')) { el.childNodes[0].nodeValue = esc(v); return; }
    el.textContent = esc(v);
  });
  document.querySelectorAll('[data-fhtml]').forEach(function (el) {
    var v = get(el.getAttribute('data-fhtml'));
    el.innerHTML = v == null || v === '' ? '' : v;
  });
  document.querySelectorAll('[data-fbg]').forEach(function (el) {
    var v = get(el.getAttribute('data-fbg'));
    if (!v) return;
    el.style.backgroundImage = "url('" + v + "')";
    if (el.classList.contains('avatar') || el.classList.contains('av')) { el.style.backgroundSize = 'cover'; el.textContent = ''; }
  });

  /* --- video bìa + điểm neo ảnh bìa ---
     `data-fvideo="<khoá>"` trỏ tới nhánh chứa {video, posterImage, focal}.
     Không có video thì KHÔNG tạo thẻ <video> nào — trang của người chưa quay
     video không phải tải thêm một byte. */
  document.querySelectorAll('[data-fvideo]').forEach(function (el) {
    var node = get(el.getAttribute('data-fvideo')) || {};
    var f = node.focal || {};
    if (f.desktop) el.style.setProperty('--fp-d', f.desktop);
    if (f.tablet) el.style.setProperty('--fp-t', f.tablet);
    if (f.mobile) el.style.setProperty('--fp-m', f.mobile);
    if (!node.video) return;
    /* Bìa video KHÔNG còn ảnh chờ RIÊNG (anh Bình chốt 15/08): video luôn phát
       ngay khi vào trang, kể cả máy bật giảm chuyển động — không có nút play,
       không có lớp che.
       Nhưng "không có ảnh chờ" khác "không có gì để nhìn": trên điện thoại
       video mất vài giây mới có byte đầu, mà `.cover.has-vid` lại đổ nền thành
       DẢI MÀU ⇒ khách mở trang bằng 4G thấy một mảng gradient trống rồi mới
       thấy hình. Nên nếu chủ trang không đặt ảnh bìa riêng (`data-fbg` ghi
       inline style, thắng luật CSS), lấy luôn `posterImage` của video làm nền:
       ảnh có sẵn trong CDN, nhẹ, hiện ngay ở khung hình đầu, và ĐÚNG BẰNG
       khung hình đầu của video nên lúc video nổi lên là khít nhau.
       KHÔNG dùng thuộc tính `poster=` cho việc này: poster biến mất ngay khi
       video có khung hình đầu, mà lúc đó lớp video còn đang mờ dần lên — hở ra
       một nhịp trắng. Nền của khối bìa thì nằm dưới, không bao giờ hở. */
    if (node.posterImage && !el.style.backgroundImage) {
      el.style.backgroundImage = "url('" + node.posterImage + "')";
    }

    var v = document.createElement('video');
    v.className = 'covervid';
    /* muted PHẢI đặt trước src và đặt cả bằng thuộc tính: Safari iOS chỉ cho tự
       phát video đã tắt tiếng ngay từ lúc gắn vào DOM. */
    v.muted = true; v.defaultMuted = true;
    v.setAttribute('muted', ''); v.setAttribute('playsinline', '');
    v.autoplay = true; v.loop = true; v.playsInline = true; v.preload = 'auto';
    v.setAttribute('autoplay', ''); v.setAttribute('loop', '');
    v.disableRemotePlayback = true;
    if (node.posterImage) v.poster = node.posterImage;
    v.src = node.video;
    el.insertBefore(v, el.firstChild);
    el.classList.add('has-vid');

    /* BÁM PHÁT (anh Bình báo 17/08: mở trong Zalo thấy đứng im ở ảnh nền).
       Gọi play() đúng MỘT lần rồi nuốt lỗi là hỏng ở trình duyệt nhúng
       (Zalo/Facebook/Instagram): lúc trang vừa dựng, webview còn chưa coi là
       "đã tương tác" hoặc video chưa có byte nào, play() bị từ chối và KHÔNG
       BAO GIỜ được gọi lại — khách ngồi nhìn tấm nền tĩnh. Nên thử lại ở mọi
       mốc video sẵn sàng hơn, lúc quay lại tab, và ở cú chạm đầu tiên. */
    /* `inView` — ảnh bìa còn nằm trong khung nhìn hay không. Cuộn qua khỏi bìa
       thì DỪNG hẳn: một thẻ <video> đang phát bắt máy giải mã + hợp thành từng
       khung hình suốt cả trang, kể cả khi nó đã trôi lên trên mép màn. Trên
       điện thoại đó là khoản nặng nhất của cả trang, và là lý do cuộn xuống
       phần dưới thấy khựng (anh Bình báo 17/08). Quay lại là phát tiếp. */
    var inView = true;
    var tryPlay = function () {
      if (!inView || document.hidden || !v.paused) return;
      var p = v.play();
      if (p && p.catch) p.catch(function () {});
    };
    ['loadeddata', 'canplay', 'canplaythrough', 'stalled', 'suspend'].forEach(function (ev) {
      v.addEventListener(ev, tryPlay);
    });
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) { if (!v.paused) v.pause(); } else tryPlay();
    });
    ['touchstart', 'pointerdown', 'click', 'scroll'].forEach(function (ev) {
      document.addEventListener(ev, tryPlay, { once: true, passive: true });
    });

    /* Mở lớp video ra ĐÚNG lúc nó thật sự có hình để vẽ. `loadeddata` là mốc
       "đã có khung hình đầu"; `playing` là chốt chặn cho webview nào bỏ qua
       mốc kia. Trước đó lớp video trong suốt, khách nhìn tấm ảnh chờ ở nền. */
    var reveal = function () { el.classList.add('vid-on'); };
    v.addEventListener('loadeddata', reveal);
    v.addEventListener('playing', reveal);
    /* Chặn cuối: video hỏng link / webview cấm hẳn autoplay thì đừng để lớp
       trong suốt che mãi — nhưng nền vẫn là ảnh chờ nên khách không mất gì. */
    v.addEventListener('error', function () { el.classList.remove('has-vid'); });

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (e) {
        inView = !!(e[0] && e[0].isIntersecting);
        if (inView) tryPlay(); else if (!v.paused) v.pause();
      }, { threshold: 0.01 }).observe(el);
    }
    tryPlay();
  });

  /* Liên kết động (gọi/Zalo/mail ở bong bóng nổi + thanh dính đáy). Không có
     dữ liệu thì giữ nguyên href dự phòng trong HTML, KHÔNG để trống. */
  document.querySelectorAll('[data-fhref]').forEach(function (el) {
    var v = get(el.getAttribute('data-fhref'));
    if (v) el.setAttribute('href', v);
  });

  /* --- danh sách: mỗi khoá có một khuôn riêng --- */
  var ICON = {
    phone: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"/></svg>',
    chat: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 5h16v11H9l-5 4z"/></svg>',
    grid: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 9v11"/></svg>',
    video: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 6h11v12H4z"/><path d="M15 10l5-3v10l-5-3z"/></svg>',
    down: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 4v12M7 11l5 5 5-5M5 20h14"/></svg>',
    cal: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16v14H4z"/><path d="M4 10h16M9 3v4M15 3v4"/></svg>',
    mail: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 6h18v12H3z"/><path d="M3 7l9 6 9-6"/></svg>',
    home: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 20V9l8-5 8 5v11z"/></svg>',
    web: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.8 3 2.8 15 0 18M12 3c-2.8 3-2.8 15 0 18"/></svg>',
    pin: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.3"/></svg>',
    user: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>',
    book: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 4h14v16l-7-3.6L5 20z"/></svg>',
    camera: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 8h3l1.5-2h7L17 8h3v11H4z"/><circle cx="12" cy="13" r="3.4"/></svg>',
    check: '<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M4 10.5l4 4 8-9"/></svg>',
    arrow: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 12h15M13 6l6 6-6 6"/></svg>',
    clock: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    lock: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>',
    star: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l2.4 5 5.6.8-4 3.9.9 5.5L12 15.6 7.1 18.2l.9-5.5-4-3.9L9.6 8z"/></svg>',
    heart: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20.4C7.4 16.6 3.5 13.3 3.5 9.4 3.5 6.7 5.5 4.8 8 4.8c1.6 0 3.1.9 4 2.3.9-1.4 2.4-2.3 4-2.3 2.5 0 4.5 1.9 4.5 4.6 0 3.9-3.9 7.2-8.5 11z"/></svg>',
    shield: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 2.8v5.1c0 4.5-2.9 8.2-7 10.1-4.1-1.9-7-5.6-7-10.1V5.8z"/><path d="M9 11.6l2.2 2.2 4-4.3"/></svg>',
    users: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9.5" cy="8.3" r="3.2"/><path d="M3.5 20c0-3.4 2.7-5.5 6-5.5s6 2.1 6 5.5"/><path d="M15.3 5.5a3.2 3.2 0 010 5.7M17.4 15c1.9.9 3.1 2.6 3.1 5"/></svg>'
  };
  var icon = function (k) { return ICON[k] || ICON.home; };

  /* `data-inline="<đường.dẫn.trong.SITE>"` = toạ độ để chế độ sửa cho bấm thẳng
     vào chữ mà sửa (data-inline-html cho ô nhận định dạng). Ngoài chế độ sửa
     các thuộc tính này nằm im, không tốn gì. Mục có `bind` là dữ liệu Hồ sơ —
     không gắn toạ độ, sửa ở Hồ sơ chứ không sửa ở đây. */
  var TPL = {
    'hero.buttons': function (b, i) {
      return '<a class="cb ' + (i === 0 ? 'solid' : 'line') + '" href="' + esc(b.href) + '">' + icon(b.icon) + ' <span data-inline="hero.buttons.' + i + '.label">' + esc(b.label) + '</span></a>';
    },
    'profile.chips': function (c, i) {
      // `icon` (vd 'web' trước địa chỉ website) đứng trước chữ; tone 'plain' = chip trần không nền.
      var ic = c.icon ? '<span class="cic">' + icon(c.icon) + '</span>' : '';
      return '<span class="chip ' + esc(c.tone || '') + '"' + (c.bind ? '' : ' data-inline="profile.chips.' + i + '.text"') + '>' + ic + esc(c.text) + '</span>';
    },
    'profile.actions': function (a, i, n) {
      var cls = a.tone ? ' ' + a.tone : '';
      if (a.primary) cls = ' main';
      return '<a class="ib' + cls + '" href="' + esc(a.href) + '" aria-label="' + esc(a.label) + '">' + icon(a.icon) + '<span class="tip">' + esc(a.label) + '</span></a>';
    },
    'nav': function (t, i) {
      return '<a class="tab' + (i === 0 ? ' on' : '') + '" href="' + esc(t.href) + '">' + icon(t.icon) + ' ' + esc(t.label) + '</a>';
    },
    'intro.facts': function (f, i) {
      /* `_i` = chỗ đứng thật trong mảng đã lưu (danh sách này bị lọc theo dữ
         liệu Hồ sơ). Dòng lấy từ Hồ sơ thì GIÁ TRỊ không cho sửa ở đây — sửa ở
         Hồ sơ mới đúng một nguồn sự thật — nhưng NHÃN vẫn tự đặt được. */
      var ix = typeof f._i === 'number' ? f._i : i;
      var val = f.bind
        ? '<b>' + esc(f.value) + '</b>'
        : '<b data-inline="intro.facts.' + ix + '.value">' + esc(f.value) + '</b>';
      return '<div class="inforow"><span class="ic">' + icon(f.icon) + '</span><span><small data-inline="intro.facts.' + ix + '.label">' + esc(f.label) + '</small>' + val + '</span></div>';
    },
    'intro.tags': function (c, i) { return '<span class="chip ' + esc(c.tone || '') + '" data-inline="intro.tags.' + i + '.text">' + esc(c.text) + '</span>'; },
    'pledge.items': function (x, i) {
      return '<div><span class="ck">' + ICON.check + '</span><span><b data-inline="pledge.items.' + i + '.title">' + esc(x.title) + '</b> — <span data-inline="pledge.items.' + i + '.text">' + esc(x.text) + '</span></span></div>';
    },
    'composer.actions': function (a, i) {
      var c = ['c1', 'c2', 'c3'][i % 3];
      return '<button type="button" onclick="location.hash=\'' + esc(a.href) + '\'"><span class="' + c + '">' + icon(a.icon) + '</span> <span data-inline="composer.actions.' + i + '.label">' + esc(a.label) + '</span></button>';
    },
    'creed.items': function (x, i) {
      return '<div class="cbox"><span class="ti">' + icon(x.icon) + '</span><h3 data-inline-html="creed.items.' + i + '.title">' + (x.title || '') + '</h3><span class="crule"></span><p data-inline="creed.items.' + i + '.text">' + esc(x.text) + '</p></div>';
    },
    'process.steps': function (x, i) {
      /* `paras` (nhiều đoạn) hoặc `text` (một đoạn) đều nhận, để quy trình cũ
         chỉ có một dòng vẫn hiện đúng. `goal` là câu chốt của bước. */
      var paras = Array.isArray(x.paras) ? x.paras : (x.text ? [x.text] : []);
      var num = ('0' + (i + 1)).slice(-2);
      /* Ảnh THẬT nằm NÓC thẻ (anh Bình cấp 5 ảnh 15/08), huy hiệu số đè lên
         mép dưới ảnh. Chỉ còn khoá `img` — bộ hình vẽ SVG `art` cũ đã bỏ hẳn,
         dữ liệu cũ còn khoá art thì mặc kệ. */
      var art = x.img
        ? '<span class="art" data-imgedit="process.steps.' + i + '.img"><img src="' + esc(x.img) + '" alt="' + esc(x.title) + '" loading="lazy" decoding="async"></span>'
        : '';
      return '<div class="step">' +
        art +
        '<span class="num">' + num + '</span>' +
        '<b data-inline="process.steps.' + i + '.title">' + esc(x.title) + '</b>' +
        paras.map(function (p, j) { return '<p data-inline="process.steps.' + i + '.paras.' + j + '">' + esc(p) + '</p>'; }).join('') +
        (x.goal ? '<p class="goal"><span class="gi">' + icon(x.goalIcon || 'check') + '</span><span><i>Mục tiêu:</i> <span data-inline="process.steps.' + i + '.goal">' + esc(x.goal) + '</span></span></p>' : '') +
        '</div>';
    },
    'form.benefits': function (t, i) { return '<div><span class="ck">' + ICON.check + '</span> <span data-inline="form.benefits.' + i + '">' + esc(t) + '</span></div>'; },
    'form.needs': function (t, i) { return '<button type="button" class="need" data-inline="form.needs.' + i + '" aria-pressed="' + (i === 0 ? 'true' : 'false') + '">' + esc(t) + '</button>'; },
    'form.pledge': function (x, i) { return '<div class="it"><span class="pi">' + icon(x.icon) + '</span> <span data-inline="form.pledge.' + i + '.text">' + esc(x.text) + '</span></div>'; },
    'cta.buttons': function (b, i) {
      return '<a class="' + (i === 0 ? 'cta1' : 'cta2') + '" href="' + esc(b.href) + '"><span data-inline="cta.buttons.' + i + '.label">' + esc(b.label) + '</span> ' + ICON.arrow + '</a>';
    },
    'footer.columns': function (c, i) {
      if (c.type === 'brand') {
        /* Ô tròn: có ảnh Hồ sơ thì hiện ảnh thật, chưa có thì rơi về chữ tắt.
           Trước địa chỉ website có icon quả cầu (anh Bình chốt 15/08). */
        var mAttr = c.avatar ? ' class="m mimg" style="background-image:url(' + esc(c.avatar) + ')"' : ' class="m"';
        return '<div><div class="fbrand"><span' + mAttr + '>' + (c.avatar ? '' : esc(c.initials)) + '</span><span><b>' + esc(c.name) + '</b><small><span class="cic">' + icon('web') + '</span>' + esc(c.domain) + '</small></span></div>' +
          '<p data-inline="footer.columns.' + i + '.desc">' + esc(c.desc) + '</p><div class="fhot">' + esc(c.hotline) + '</div><p style="margin-top:4px">' + esc(c.email) + '</p></div>';
      }
      return '<div><h4 data-inline="footer.columns.' + i + '.title">' + esc(c.title) + '</h4><div class="flist">' +
        (c.links || []).map(function (l) { return '<a href="' + esc(l.href) + '"><i></i>' + esc(l.label) + '</a>'; }).join('') + '</div></div>';
    }
  };

  document.querySelectorAll('[data-flist]').forEach(function (el) {
    var key = el.getAttribute('data-flist');
    var arr = get(key);
    if (!Array.isArray(arr) || !arr.length || !TPL[key]) return;
    var keep = el.hasAttribute('data-fkeep') ? el.firstElementChild : null;
    var html = arr.map(function (x, i) { return TPL[key](x, i, arr.length); }).join('');
    el.innerHTML = (keep ? keep.outerHTML : '') + html;
  });

  /* tiêu đề trang + favicon */
  if (S.seo && S.seo.title) document.title = S.seo.title;
})();

</script>
{/literal}
{literal}
<script>

(function(){
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  /* Đang trong trình sửa? (?edit=1 khi mở trực tiếp, cờ toàn cục khi nhúng srcdoc) */
  var EDITING = /[?&]edit=1\b/.test(location.search) || !!window.__GH_EDIT__;
  /* Link chia sẻ dự án cần userId 24-hex (server resolveSharer) — refId là slug,
     chỉ dùng cho link 360. Bản ghi cũ chưa có shareRef thì đành rơi về refId. */
  var REF = (SITE.profile && (SITE.profile.shareRef || SITE.profile.refId)) || '';

  /* Dữ liệu 6 dự án — đồng bộ từ quỹ hàng G-HUB (server.g-hub.vn) */
  var P = (SITE.projects && SITE.projects.items) || [];
  var shareUrl = function(p){ return p.url || ('https://g-hub.vn/du-an/' + p.slug + (REF ? '?ref=' + REF : '')); };
  /* Xem dự án NGAY TRONG giao diện đang đứng, không mở tab mới:
     - trong khung quản lý (iframe) → báo khung cha điều hướng SPA tới trang
       chia sẻ /du-an/<slug>?ref= (bấm Back của trình duyệt là về lại đây);
     - trên trang thật → đi cùng tab, nút Back của trình duyệt quay về trang này. */
  var openProject = function(p){
    if (EDITING) return; // đang sửa thì đừng văng khỏi trình sửa
    if (window.self !== window.top) {
      parent.postMessage({ source: 'gh-personal-site', action: 'open-project', slug: p.slug, ref: REF, url: shareUrl(p) }, '*');
      return;
    }
    location.href = shareUrl(p);
  };

  /* ---------- hộp phát video (nhúng YouTube/Vimeo/TikTok hoặc tệp tải lên) ---------- */
  var ytId = function(url){
    var m = String(url || '').match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|shorts\/|embed\/|live\/)|youtu\.be\/)([\w-]{6,})/);
    return m ? m[1] : '';
  };
  var vimeoId = function(url){
    var m = String(url || '').match(/vimeo\.com\/(?:video\/)?(\d{6,})/);
    return m ? m[1] : '';
  };
  var isFileVideo = function(url){ return /\.(mp4|webm|mov|m4v)(\?|$)/i.test(String(url || '')); };
  /* TikTok (anh Bình chốt 18/08). Link đầy đủ nào cũng mang id ở khúc cuối:
     /@nguoi/video/<id> · /video/<id> · /v/<id>.html · /embed/v2/<id> ·
     /player/v1/<id>. Link RÚT GỌN (vm./vt.tiktok.com/AbCdE) KHÔNG mang id —
     phải nhờ máy chủ dịch, xem `tiktokShort` + `/public/nguoi/_tiktok/link`. */
  var tiktokId = function(url){
    var m = String(url || '').match(/tiktok\.com\/(?:@[\w.-]+\/)?(?:video|v|embed(?:\/v2)?|player\/v1)\/(\d{6,25})/);
    return m ? m[1] : '';
  };
  var tiktokShort = function(url){ return /^(?:https?:)?\/\/(?:vm|vt)\.tiktok\.com\/[\w-]{4,}/i.test(String(url || '').trim()); };
  /* Clip DỌC — TikTok và YouTube Shorts đi CHUNG một đường hiển thị (anh Bình
     chốt 18/08): thẻ vẫn 16:9 như mọi thẻ khác nhưng ảnh bìa NẰM TRỌN ở giữa
     trên chính nó làm mờ, và trình chiếu thu về khung 9:16 giữa màn thay vì
     kéo ngang. Dán link TikTok hay link Shorts đều ra đúng một kiểu, không cái
     nào bị cắt mất đầu/chân. */
  var isVertVideo = function(url){
    return !!tiktokId(url) || /youtube\.com\/shorts\//i.test(String(url || ''));
  };
  /* Gốc API suy từ chính địa chỉ gửi form (`assets.leadUrl` luôn tuyệt đối):
     trang chạy ở g-hub.vn còn API ở server.g-hub.vn, mà bản xem trước lại nằm
     trong <iframe srcdoc> KHÔNG có gốc nào — đường dẫn tương đối chết cả hai
     chỗ, nên phải dựng tuyệt đối. */
  var apiBase = function(){
    var m = String((window.SITE && SITE.assets && SITE.assets.leadUrl) || '').match(/^(https?:\/\/[^/]+)/);
    return m ? m[1] : '';
  };
  /* Ảnh bìa suy từ link nhúng — YouTube/Vimeo đều có ảnh sẵn theo id video. */
  var thumbFromUrl = function(url){
    var y = ytId(url);
    if (y) return 'https://img.youtube.com/vi/' + y + '/hqdefault.jpg';
    var v = vimeoId(url);
    if (v) return 'https://vumbnail.com/' + v + '.jpg';
    /* TikTok trả ảnh bìa là URL KÝ HẠN (x-expires vài giờ) ⇒ lưu thẳng là hôm
       sau thẻ trắng ảnh. Lưu đường dẫn của MÌNH, máy chủ xin URL ký mới lúc
       khách xem. Không suy ra được gốc API thì thà để trống còn hơn lưu một
       đường dẫn tương đối chết ở g-hub.vn. */
    var tk = tiktokId(url);
    if (tk) { var b = apiBase(); return b ? b + '/public/nguoi/_tiktok/anh/' + tk : ''; }
    return '';
  };
  /* Ảnh bìa ĐÃ LƯU có thể là link TRANG video (dữ liệu dán trước khi trình sửa
     biết tự quy đổi) — dựng trang thì quy nốt tại đây; link ảnh thường giữ nguyên. */
  var coverImg = function(u){ return thumbFromUrl(u) || u || ''; };
  /* NGƯỢC LẠI của thumbFromUrl: từ ảnh bìa YouTube/Vimeo suy lại link video —
     cứu bản ghi dán link vào ô ẢNH BÌA nên url bị bỏ trống, nút phát vẫn chạy. */
  var urlFromThumb = function(u){
    var m = String(u || '').match(/(?:img\.youtube\.com|i\.ytimg\.com)\/vi\/([\w-]{6,})/);
    if (m) return 'https://www.youtube.com/watch?v=' + m[1];
    m = String(u || '').match(/vumbnail\.com\/(\d{6,})/);
    if (m) return 'https://vimeo.com/' + m[1];
    m = String(u || '').match(/\/_tiktok\/anh\/(\d{6,25})/);
    /* `@i` là tên người dùng GIẢ — oEmbed lẫn trình phát TikTok chỉ soi id, tên
       trong đường dẫn là gì cũng ra đúng video. */
    if (m) return 'https://www.tiktok.com/@i/video/' + m[1];
    return '';
  };
  /* RULE gắn link video: dán kiểu gì cũng quy về URL đầy đủ trước khi lưu/phát
     (thừa khoảng trắng, thiếu https://, //youtu.be/…, Shorts/embed đã có ytId lo). */
  var normVideoUrl = function(u){
    u = String(u || '').trim();
    if (!u) return '';
    if (/^\/\//.test(u)) return 'https:' + u;
    if (!/^[a-z][\w+.-]*:/i.test(u)) return 'https://' + u;
    return u;
  };
  /* Link để PHÁT của một video: url đã lưu, không có thì suy từ ảnh bìa. */
  var playUrl = function(v){ return normVideoUrl((v && v.url) || '') || urlFromThumb(v && v.img); };
  /* ---------- trình chiếu video FULL MÀN HÌNH ----------
     Bấm phát là mở lớp phủ kín cửa sổ. Tệp mp4 và YouTube dùng thanh điều khiển
     tự dựng (tạm dừng · tua · âm lượng · 0.5–2x); YouTube phải đi qua IFrame API
     mới giấu được controls gốc — API bị chặn (srcdoc trình sửa có CSP
     script-src) thì rơi về iframe thường. Vimeo/link nhúng khác không cho giấu
     controls nên giữ trình phát gốc. KHÔNG có nút phóng to (anh Bình chốt
     16/08): máy cảm ứng xoay ngang là tự lên fullscreen, xoay dọc là hạ. */
  var GH_IP = null; /* player đang mở — mỗi lúc chỉ một video chạy */
  var ytApiWait = null;
  var loadYtApi = function(){
    if (window.YT && window.YT.Player) return Promise.resolve();
    if (ytApiWait) return ytApiWait;
    ytApiWait = new Promise(function(ok, fail){
      var t = setTimeout(function(){ fail(new Error('yt-timeout')); }, 2500);
      window.onYouTubeIframeAPIReady = function(){ clearTimeout(t); ok(); };
      var s = document.createElement('script');
      s.src = 'https://www.youtube.com/iframe_api';
      s.onerror = function(){ clearTimeout(t); fail(new Error('yt-blocked')); };
      document.head.appendChild(s);
    });
    return ytApiWait;
  };
  /* Nạp sẵn YT API ở lần tương tác ĐẦU TIÊN (chỉ khi trang có video YouTube):
     lúc khách bấm play API đã sẵn → player dựng NGAY TRONG cử chỉ chạm nên
     mobile cho phát CÓ TIẾNG; để đến lúc bấm mới tải là quá cửa sổ cử chỉ,
     trình duyệt ép tắt tiếng. */
  (function(){
    var v = (window.SITE || {}).video || {};
    var has = ytId((v.hero || {}).url) || (v.items || []).some(function(it){ return ytId(it.url); });
    if (!has) return;
    var pre = function(){ loadYtApi().catch(function(){}); };
    ['pointerdown', 'touchstart', 'scroll', 'keydown'].forEach(function(n){
      window.addEventListener(n, pre, { passive: true, once: true });
    });
  })();
  var fmtT = function(s){
    s = Math.max(0, Math.round(s || 0));
    return Math.floor(s / 60) + ':' + ('0' + (s % 60)).slice(-2);
  };
  var SVG_PP = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 5h3.4v14H7zM13.6 5H17v14h-3.6z"/></svg>';
  var SVG_PL = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
  var SVG_VO = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 9v6h4l5 4V5L8 9H4z"/><path d="M16.5 8.5a5 5 0 0 1 0 7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
  var SVG_MU = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 9v6h4l5 4V5L8 9H4z"/><path d="M16 9.5l5 5M21 9.5l-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/></svg>';
  var stopInline = function(){ if (GH_IP) { var d = GH_IP.destroy; GH_IP = null; d(); } };
  /* Bọc lời gọi YT.Player: trước onReady một số method chưa tồn tại. */
  var safe = function(fn, dflt){ try { return fn(); } catch (e) { return dflt; } };

  var htmlAdapter = function(vd){
    return {
      toggle: function(){ if (vd.paused || vd.ended) vd.play(); else vd.pause(); },
      playing: function(){ return !vd.paused && !vd.ended; },
      seek: function(t){ vd.currentTime = t; },
      cur: function(){ return vd.currentTime || 0; },
      dur: function(){ return vd.duration || 0; },
      setVol: function(v){ vd.volume = v; vd.muted = v === 0; },
      getVol: function(){ return vd.muted ? 0 : vd.volume; },
      setRate: function(r){ vd.playbackRate = r; }
    };
  };
  var ytAdapter = function(pl){
    return {
      toggle: function(){ safe(function(){ if (pl.getPlayerState() === 1) pl.pauseVideo(); else pl.playVideo(); }); },
      playing: function(){ return safe(function(){ return pl.getPlayerState() === 1; }, false); },
      seek: function(t){ safe(function(){ pl.seekTo(t, true); }); },
      cur: function(){ return safe(function(){ return pl.getCurrentTime(); }, 0) || 0; },
      dur: function(){ return safe(function(){ return pl.getDuration(); }, 0) || 0; },
      setVol: function(v){ safe(function(){ if (v === 0) { pl.mute(); } else { pl.unMute(); pl.setVolume(Math.round(v * 100)); } }); },
      getVol: function(){ return safe(function(){ return pl.isMuted() ? 0 : (pl.getVolume() || 0) / 100; }, 1); },
      setRate: function(r){ safe(function(){ pl.setPlaybackRate(r); }); }
    };
  };

  /* Thanh điều khiển: hàng 1 = thời gian + tua, hàng 2 = tạm dừng · âm lượng ·
     tốc độ. Tự ẩn sau 2.6s khi đang phát, luôn hiện khi tạm dừng. */
  var attachBar = function(ip, state, ad){
    var shield = document.createElement('div');
    shield.className = 'shield';
    ip.appendChild(shield);
    var bar = document.createElement('div');
    bar.className = 'gh-vc';
    bar.innerHTML =
      '<div class="r1"><span class="tc">0:00</span>' +
        '<input class="seek" type="range" min="0" max="1000" value="0" aria-label="Tua video">' +
        '<span class="td">0:00</span></div>' +
      '<div class="r2"><button type="button" class="pp" aria-label="Tạm dừng / phát">' + SVG_PP + '</button>' +
        '<button type="button" class="mu" aria-label="Tắt / bật tiếng">' + SVG_VO + '</button>' +
        '<input class="vol" type="range" min="0" max="100" value="100" aria-label="Âm lượng">' +
        '<span class="gap"></span>' +
        [0.5, 1, 1.5, 2].map(function(r){
          return '<button type="button" class="rt' + (r === 1 ? ' on' : '') + '" data-r="' + r + '">' + r + 'x</button>';
        }).join('') + '</div>';
    ip.appendChild(bar);
    var seek = bar.querySelector('.seek'), vol = bar.querySelector('.vol'),
        tc = bar.querySelector('.tc'), td = bar.querySelector('.td'),
        pp = bar.querySelector('.pp'), mu = bar.querySelector('.mu');
    var fill = function(el, pct){
      el.style.background = 'linear-gradient(90deg,#fff ' + pct + '%,rgba(255,255,255,.34) ' + pct + '%)';
    };
    fill(seek, 0); fill(vol, 100);
    var hideT = 0;
    var wake = function(){
      ip.classList.add('ctl');
      clearTimeout(hideT);
      hideT = setTimeout(function(){ if (ad.playing()) ip.classList.remove('ctl'); }, 2600);
    };
    ip.addEventListener('mousemove', wake);
    ip.addEventListener('touchstart', wake, { passive: true });
    shield.addEventListener('click', function(){ ad.toggle(); wake(); });
    pp.addEventListener('click', function(){ ad.toggle(); wake(); });
    seek.addEventListener('input', function(){
      var d = ad.dur();
      if (d) ad.seek(d * seek.value / 1000);
      fill(seek, seek.value / 10); wake();
    });
    var lastVol = 1;
    var volIcon = function(){ mu.innerHTML = ad.getVol() > 0 ? SVG_VO : SVG_MU; };
    mu.addEventListener('click', function(){
      var v = ad.getVol();
      if (v > 0) { lastVol = v; ad.setVol(0); vol.value = 0; }
      else { ad.setVol(lastVol || 1); vol.value = Math.round((lastVol || 1) * 100); }
      fill(vol, vol.value); volIcon(); wake();
    });
    vol.addEventListener('input', function(){
      ad.setVol(vol.value / 100); fill(vol, vol.value); volIcon(); wake();
    });
    bar.querySelectorAll('.rt').forEach(function(b){
      b.addEventListener('click', function(){
        ad.setRate(parseFloat(b.dataset.r));
        bar.querySelectorAll('.rt').forEach(function(x){ x.classList.toggle('on', x === b); });
        wake();
      });
    });
    state.timer = setInterval(function(){
      var d = ad.dur(), c = ad.cur();
      tc.textContent = fmtT(c); td.textContent = fmtT(d);
      if (!seek.matches(':active')) {
        seek.value = d ? Math.round(c / d * 1000) : 0;
        fill(seek, seek.value / 10);
      }
      /* Đồng bộ loa theo trạng thái thật — autoplay bị chặn sẽ tự rơi về tắt
         tiếng, thanh phải hiện đúng để khách biết bấm loa bật lại. */
      if (!vol.matches(':active')) {
        var gv = Math.round(ad.getVol() * 100);
        if (String(gv) !== vol.value) { vol.value = gv; fill(vol, gv); }
        volIcon();
      }
      var on = ad.playing();
      pp.innerHTML = on ? SVG_PP : SVG_PL;
      if (!on) ip.classList.add('ctl');
    }, 300);
    wake();
  };

  var ghCinePlay = function(url, title){
    if (!url) return;
    stopInline();
    var ip = document.createElement('div');
    ip.className = 'gh-cine' + (isVertVideo(url) ? ' vert' : '');
    ip.innerHTML = '<div class="med"></div>' +
      '<div class="cap"></div>' +
      '<div class="gh-tr">' +
        '<button type="button" class="cl" aria-label="Đóng video">✕</button>' +
      '</div>';
    if (title) ip.querySelector('.cap').textContent = title;
    document.body.appendChild(ip);
    var med = ip.querySelector('.med');
    var state = { dead: false, timer: 0, player: null, destroy: null };
    /* Khoá cuộn trang khi trình chiếu mở — đặt trên <html> chứ không phải body
       (trang này body không phải hộp cuộn), đóng thì trả lại nguyên trạng. */
    var prevOverflow = document.documentElement.style.overflow;
    document.documentElement.style.overflow = 'hidden';
    var fsEl = function(){ return document.fullscreenElement || document.webkitFullscreenElement; };
    var onKey = function(e){ if (e.key === 'Escape' && !fsEl()) stopInline(); };
    /* Máy cảm ứng: xoay NGANG là tự nâng lên fullscreen trình duyệt, xoay dọc
       lại thì hạ xuống. requestFullscreen ngoài cử chỉ chạm có thể bị từ chối
       (BẤT ĐỒNG BỘ qua Promise — phải .catch); khi đó thôi, lớp phủ vốn đã
       kín cửa sổ. iPhone không có requestFullscreen trên <div> thì mp4 rơi về
       trình phát hệ thống. */
    var isTouch = window.matchMedia && matchMedia('(pointer: coarse)').matches;
    var onTurn = function(){
      var o = screen.orientation && screen.orientation.type;
      var land = o ? o.indexOf('landscape') === 0 : innerWidth > innerHeight;
      if (land && !fsEl()) {
        var req = ip.requestFullscreen || ip.webkitRequestFullscreen;
        if (req) {
          try { var r = req.call(ip); if (r && r.catch) r.catch(function(){}); } catch (e) {}
        } else {
          var vd = med.querySelector('video');
          if (vd && vd.webkitEnterFullscreen) safe(function(){ vd.webkitEnterFullscreen(); });
        }
      } else if (!land && fsEl() === ip) {
        safe(function(){ (document.exitFullscreen || document.webkitExitFullscreen).call(document); });
      }
    };
    state.destroy = function(){
      if (state.dead) return;
      state.dead = true;
      clearInterval(state.timer);
      document.removeEventListener('keydown', onKey);
      if (isTouch) {
        window.removeEventListener('orientationchange', onTurn);
        window.removeEventListener('resize', onTurn);
      }
      document.documentElement.style.overflow = prevOverflow;
      if (fsEl() === ip) safe(function(){ (document.exitFullscreen || document.webkitExitFullscreen).call(document); });
      if (state.player && state.player.destroy) safe(function(){ state.player.destroy(); });
      if (ip.parentNode) ip.parentNode.removeChild(ip);
    };
    GH_IP = { destroy: state.destroy };
    document.addEventListener('keydown', onKey);
    if (isTouch) {
      window.addEventListener('orientationchange', onTurn);
      window.addEventListener('resize', onTurn);
      /* Đang cầm ngang sẵn mà bấm phát: lên full luôn — còn nguyên cử chỉ chạm. */
      onTurn();
    }
    ip.querySelector('.cl').addEventListener('click', function(e){ e.stopPropagation(); stopInline(); });
    var y = ytId(url), vm = vimeoId(url), tk = tiktokId(url);
    if (isFileVideo(url)) {
      var vd = document.createElement('video');
      vd.playsInline = true; vd.autoplay = true; vd.src = url;
      med.appendChild(vd);
      /* Mobile chặn autoplay CÓ TIẾNG — bị từ chối thì tắt tiếng phát luôn,
         khách bật lại bằng nút loa (thanh điều khiển tự đồng bộ trạng thái). */
      safe(function(){
        var p = vd.play();
        if (p && p.catch) p.catch(function(){ vd.muted = true; safe(function(){ vd.play(); }); ip.classList.add('ctl'); });
      });
      attachBar(ip, state, htmlAdapter(vd));
    } else if (y) {
      loadYtApi().then(function(){
        if (state.dead) return;
        var hostEl = document.createElement('div');
        med.appendChild(hostEl);
        var pl = new YT.Player(hostEl, {
          /* PHẢI ghim host nocookie: bỏ trống thì IFrame API nhúng từ
             www.youtube.com ⇒ mang theo phiên đăng nhập YouTube của KHÁCH ⇒
             khách nào dùng gói Premium gia đình đang phát nhiều máy sẽ ăn
             "Đã tạm dừng phát lại vì hiện có quá nhiều thiết bị…" ngay giữa
             trang mình. Miền nocookie phát ẩn danh nên không dính hạn mức
             tài khoản của ai cả (nhánh dự phòng bên dưới vốn đã dùng miền này). */
          host: 'https://www.youtube-nocookie.com',
          width: '100%', height: '100%', videoId: y,
          playerVars: { autoplay: 1, controls: 0, rel: 0, playsinline: 1, modestbranding: 1, disablekb: 1, fs: 0, iv_load_policy: 3 },
          events: { onReady: function(ev){
            safe(function(){ ev.target.playVideo(); });
            /* Mobile chặn autoplay CÓ TIẾNG nên playVideo() bị nuốt — video
               nằm im ở ảnh bìa chờ bấm play lần nữa. Sau 700ms chưa chạy
               (1=đang phát, 3=đang nạp) thì tắt tiếng rồi phát lại ngay. */
            setTimeout(function(){
              if (state.dead) return;
              safe(function(){
                var st = ev.target.getPlayerState();
                if (st !== 1 && st !== 3) { ev.target.mute(); ev.target.playVideo(); ip.classList.add('ctl'); }
              });
            }, 700);
          } }
        });
        state.player = pl;
        attachBar(ip, state, ytAdapter(pl));
      }).catch(function(){
        if (state.dead) return;
        med.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + y + '?autoplay=1&rel=0&playsinline=1" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
      });
    } else if (vm) {
      med.innerHTML = '<iframe src="https://player.vimeo.com/video/' + vm + '?autoplay=1" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
    } else if (tk) {
      /* TikTok quay DỌC: trình phát của họ tự chèn hai dải đen hai bên trong
         khung ngang, mình không phải căn gì. `music_info`/`description` tắt để
         khỏi đè chữ lên video; giữ trình phát gốc như Vimeo vì TikTok không cho
         giấu controls. */
      med.innerHTML = '<iframe src="https://www.tiktok.com/player/v1/' + tk + '?autoplay=1&music_info=0&description=0&rel=0" allow="autoplay; fullscreen; encrypted-media; picture-in-picture" allowfullscreen></iframe>';
    } else {
      med.innerHTML = '<iframe src="' + url.replace(/"/g, '&quot;') + '" allow="autoplay; fullscreen" allowfullscreen></iframe>';
    }
  };
  /* `tone` là lớp gradient lót dưới ảnh. Mục do người dùng tự thêm ở trình sửa
     KHÔNG có tone — nối chuỗi thẳng sẽ ra "url(...), " (thiếu vế sau), cả khai
     báo background-image hỏng và thẻ dự án mất luôn ảnh. Thiếu thì bỏ vế đó. */
  var TONE_FALLBACK = 'linear-gradient(140deg,#e6ecf8,#ffe6d3)';
  var bg = function(p){
    var tone = p.tone || TONE_FALLBACK;
    return p.img ? "url('" + p.img + "'), " + tone : tone;
  };

  /* ---------- video theo dự án ---------- */
  var vg = document.getElementById('vgrid'), vchips = document.getElementById('vchips'),
      vProj = 'all', vQ = '';
  /* Dải video chạy ngang (ghPinRail) — gán ở cuối file, dùng để đo lại mỗi khi
     số thẻ đổi (lọc dự án, tìm kiếm, thêm/xoá video trong trình sửa). */
  var GH_VRAIL = null;
  /* Mọi field đều rơi về chuỗi rỗng: video do người dùng tự thêm có thể bỏ
     trống bất kỳ ô nào, mà bên dưới có `v.title.toLowerCase()` — thiếu là cả
     khối video chết trắng. `srcIndex` giữ CHỈ SỐ GỐC trong SITE.video.items để
     trình sửa biết mình đang sửa/xoá mục nào sau khi lọc/tìm kiếm. */
  /* Nền của một thẻ video. Clip dọc dùng ẢNH THẬT hai lần: lớp trên fit trọn
     (CSS `.vid.vert`), lớp dưới là chính nó phóng to + làm mờ qua biến `--vimg`
     — đúng lối YouTube bày Shorts, chứ cắt cover thì mặt người bay mất. */
  var vidStyle = function(v){
    /* Clip dọc KHÔNG đặt nền cho chính thẻ: hai lớp (mờ + sắc) đều dựng từ
       `--vimg` ở CSS, đặt thêm nền chỉ tổ nằm khuất dưới lớp mờ. */
    if (v.vert) return "--vimg:url('" + String(v.img).replace(/'/g, '%27') + "')";
    return 'background-image:' + bg({ img: v.img, tone: v.tone });
  };
  var buildVlist = function(){
    return ((SITE.video && SITE.video.items) || []).map(function(v, idx){
      var p = P.filter(function(x){ return x.n === v.proj; })[0] || P[0] || {};
      var vurl = playUrl(v);
      var vimg = coverImg(v.img || p.img);
      return {
        srcIndex: idx,
        proj: v.proj || p.n || '', img: vimg, tone: v.tone || p.tone || '',
        title: v.title || '', sub: v.sub || '', dur: v.dur || '', dev: v.dev || p.dev || '', url: vurl,
        /* Ảnh bìa TikTok/Shorts là ảnh DỌC — không có ảnh thì đừng bật lối dọc,
           nền mờ lấy từ chính ảnh nên trống là ra khung đen trơ. */
        vert: !!vimg && isVertVideo(vurl)
      };
    });
  };
  var VLIST = buildVlist();
  /* Trình sửa cắm hai móc này (sửa/xoá một video) khi chạy ở chế độ sửa. */
  var GH_VHOOKS = null;
  /* Chồng hồ sơ video (điện thoại) tự cắm móc vào đây khi dựng xong — phần lưới
     bên dưới gọi lại để chồng thẻ bám theo dữ liệu/bộ lọc mới. */
  var GH_DECK = null;
  /* Video nổi bật: ảnh + thời lượng đều lấy từ dữ liệu. Trước đây thời lượng bị
     ghi cứng "03:24" trong HTML nên trang của ai cũng hiện đúng con số đó. */
  var vheroEl = document.getElementById('vhero');
  var vheroMain = document.getElementById('vheromain');
  var vheroProjEl = document.getElementById('vheroproj');
  /* Trang ĐÃ CÓ video trong dải mà CHƯA chọn video nổi bật (ảnh, link, thời
     lượng, dự án, chú thích đều trống) thì khối này MƯỢN video mới nhất — mục
     đầu của danh sách — làm video nổi bật: ảnh, thời lượng, link phát, chip dự
     án và chú thích đều lấy từ mục đó.

     Vì sao phải mượn: khối nổi bật là thứ đầu tiên người xem thấy ở vùng video.
     Chưa chọn thì trước đây nó đứng RỖNG — một khung xám cao gần nửa màn hình,
     nút phát bấm không ra gì, lại kéo hết bề ngang vì cột chữ bên phải trống
     nên không đủ điều kiện chia đôi (18/08: trang nguyen-trung-hieu có 3 video
     mà khối nổi bật vẫn trắng trơn, full ngang).

     CHỈ mượn ở chế độ XEM. Vào trình sửa thì trả khối về đúng dữ liệu thật của
     trang, vì chú thích được lưu THEO DOM (data-fhtml): bày chữ mượn ra đó là
     chủ trang bấm Lưu một cái thành ghi đè thật vào dữ liệu của mình. Chữ mượn
     luôn mang class `autocap` để lượt dựng sau còn biết ô này vẫn đang trống. */
  function autoHero(){
    if (EDITING) return null;
    var h = (SITE.video && SITE.video.hero) || {};
    if (h.img || h.url || h.dur || h.proj || heroCapText()) return null;
    return ((SITE.video && SITE.video.items) || [])[0] || null;
  }
  /* Chú thích THẬT của trang — chữ mượn không tính. */
  function heroCapText(){
    var cap = document.getElementById('vherocap');
    if (!cap || cap.classList.contains('autocap')) return '';
    return cap.textContent.trim();
  }
  /* Chú thích mượn: chữ chính = tên video, dòng phụ = phụ đề của mục đó. Dựng
     bằng DOM chứ không innerHTML — tên video là chữ người dùng tự nhập. */
  function fillAutoCap(it){
    var cap = document.getElementById('vherocap');
    if (!cap) return;
    if (!it) { if (cap.classList.contains('autocap')) { cap.classList.remove('autocap'); cap.textContent = ''; } return; }
    if (heroCapText()) return;
    cap.classList.add('autocap');
    cap.textContent = it.title || '';
    if (it.sub) { var sp = document.createElement('span'); sp.textContent = it.sub; cap.appendChild(sp); }
  }
  /* Cột chữ bên phải: chip dự án + chú thích + nút xem. Chia đôi CHỈ khi cột đó
     có thứ gì để đọc — trang chưa viết chú thích, chưa gắn dự án thì để ảnh
     chạy hết bề ngang như cũ, đỡ hở nửa khung trắng. Trong trình sửa thì luôn
     chia đôi: không thấy cột phải thì chủ trang chẳng biết gõ chú thích ở đâu. */
  function syncHeroSide(vhero){
    var proj = vhero.proj || '';
    if (vheroProjEl) {
      vheroProjEl.querySelector('.nm').textContent = proj;
      vheroProjEl.hidden = !proj;
    }
    if (!vheroMain) return;
    var cap = document.getElementById('vherocap');
    var hasText = !!proj || !!(cap && cap.textContent.trim());
    vheroMain.classList.toggle('vsplit', hasText || EDITING);
  }
  /* Dữ liệu khối nổi bật đang hiển thị (thật hoặc mượn) — nút phát bám theo nó. */
  var HERO = {};
  function refreshHero(){
    var auto = autoHero();
    fillAutoCap(auto);
    HERO = auto || (SITE.video && SITE.video.hero) || {};
    syncHeroSide(HERO);
    if (!vheroEl) return;
    var heroImg = coverImg(HERO.img);
    var heroVert = !!heroImg && isVertVideo(playUrl(HERO));
    vheroEl.classList.toggle('vert', heroVert);
    vheroEl.style.setProperty('--vimg', heroVert ? "url('" + heroImg.replace(/'/g, '%27') + "')" : '');
    vheroEl.style.backgroundImage = heroVert ? 'none' : bg({ img: heroImg, tone: TONE_FALLBACK });
    var vheroDur = vheroEl.querySelector('.dur');
    if (vheroDur) { vheroDur.textContent = HERO.dur || ''; vheroDur.style.display = HERO.dur ? '' : 'none'; }
  }
  refreshHero();
  /* Tiêu đề trình chiếu = phần chữ đầu của caption (trước <span> phụ đề). */
  function playHero(){
    var cap = document.getElementById('vherocap');
    var t = cap && cap.childNodes[0] && cap.childNodes[0].nodeType === 3 ? cap.childNodes[0].textContent : '';
    ghCinePlay(playUrl(HERO), t);
  }
  if (vheroEl) {
    var vheroPlay = vheroEl.querySelector('.play');
    if (vheroPlay) vheroPlay.addEventListener('click', playHero);
  }
  function refreshVcount(){ document.getElementById('vcount').textContent = VLIST.length + ' video'; }
  refreshVcount();

  function renderVideos(){
    var rows = VLIST.filter(function(v){
      return (vProj === 'all' || v.proj === vProj) &&
             (v.title.toLowerCase().indexOf(vQ) >= 0 || v.sub.toLowerCase().indexOf(vQ) >= 0);
    });
    /* Trước đây cắt còn 6 thẻ rồi mở tiếp bằng nút "Xem thêm video". Nay dải
       chạy ngang nên bày HẾT: muốn xem thêm thì cứ cuộn tiếp. */
    var shown = rows;
    vg.innerHTML = '';
    if (!shown.length) { vg.innerHTML = '<div class="vempty">Chưa có video nào khớp bộ lọc.</div>'; }
    else shown.forEach(function(v){
      var d = document.createElement('div');
      d.className = 'vitem';
      /* markCurrentProject() đọc khoá này để biết thẻ đang dẫn đầu khung nhìn
         thuộc dự án nào rồi sáng chip tương ứng. */
      d.dataset.proj = v.proj || '';
      d.innerHTML =
        '<figure class="vid' + (v.vert ? ' vert' : '') + '" style="' + vidStyle(v) + '">' +
          '<button class="play sm" aria-label="Phát video ' + v.proj + '"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></button>' +
          (v.dur ? '<span class="dur">' + v.dur + '</span>' : '') + '</figure>' +
        '<b>' + v.title + '</b><small>' + [v.sub, v.dev].filter(Boolean).join(' · ') + '</small>';
      /* Tên bị cắt còn 3 dòng (xem CSS `.vitem b`) — giữ tên đầy đủ ở tooltip.
         Gán bằng thuộc tính chứ không nhét vào chuỗi HTML: tên video có dấu
         nháy kép là vỡ thẻ. */
      var vTitleEl = d.querySelector('b');
      if (vTitleEl) vTitleEl.title = v.title || '';
      d.querySelector('.play').addEventListener('click', function(){ ghCinePlay(v.url, v.title); });
      /* Chế độ sửa: mỗi thẻ video có nút Sửa/Xoá ngay trên thẻ. */
      if (EDITING) {
        var fig = d.querySelector('.vid');
        var tools = document.createElement('span');
        tools.className = 'gh-vtools';
        var be = document.createElement('button'); be.type = 'button'; be.textContent = 'Sửa';
        var bd = document.createElement('button'); bd.type = 'button'; bd.className = 'del'; bd.textContent = 'Xoá';
        be.addEventListener('click', function(e){ e.stopPropagation(); if (GH_VHOOKS) GH_VHOOKS.edit(v.srcIndex); });
        bd.addEventListener('click', function(e){
          e.stopPropagation();
          /* Xác nhận ngay trên nút — bấm lần nữa trong 3 giây là xoá thật. */
          if (bd.dataset.arm) { if (GH_VHOOKS) GH_VHOOKS.remove(v.srcIndex); return; }
          bd.dataset.arm = '1'; bd.textContent = 'Xoá?';
          setTimeout(function(){ delete bd.dataset.arm; bd.textContent = 'Xoá'; }, 3000);
        });
        tools.appendChild(be); tools.appendChild(bd);
        fig.appendChild(tools);
      }
      vg.appendChild(d);
    });
    /* Bố cục 2 HÀNG: số cột = nửa số thẻ (làm tròn lên), tối thiểu 3 cột để
       danh sách ngắn vẫn trải đúng như lưới cũ. Dòng chảy là row ⇒ hàng trên
       nhận nửa đầu danh sách theo đúng thứ tự trái→phải, hàng dưới nửa sau. */
    vg.style.setProperty('--vcols', Math.max(3, Math.ceil(shown.length / 2)));
    /* Số thẻ vừa đổi ⇒ quãng đường ngang đổi theo. ResizeObserver của dải KHÔNG
       bắt được việc này: bề ngang của lưới luôn bằng khung bọc, thêm bớt thẻ
       chỉ đổi scrollWidth. Phải tự gọi đo lại. */
    if (GH_VRAIL) GH_VRAIL.remeasure();
  }

  /* Chip dự án đang dẫn đầu khung nhìn — trả lời câu "đang kéo tới dự án nào
     rồi". Lấy thẻ ĐẦU TIÊN còn nhìn thấy tính từ mép trái của lớp dính (thẻ
     nào trôi hẳn ra ngoài thì bỏ qua), rồi sáng chip cùng tên. Kéo luôn hàng
     chip cho chip đó lọt vào tầm mắt bằng scrollLeft — KHÔNG dùng
     scrollIntoView: hàm đó cuộn cả trang, mà trang đang bị ghim. */
  /* Tên dự án ĐANG sáng. Hàm dưới chạy mỗi khung hình lúc dải đang trôi, nên
     phải im lặng tuyệt đối khi chưa có gì đổi: sờ vào class hay scrollLeft là
     bắt trình duyệt tính lại bố cục + vẽ lại hàng chip 60 lần/giây, chính nó
     làm dải chạy ngang thấy giật (anh Bình báo 17/08). */
  var VPROJ_NOW = null;
  function markCurrentProject(){
    if (!vchips) return;
    var stage = vg.parentNode;
    var edge = stage.getBoundingClientRect().left;
    var cur = '', kids = vg.children;
    for (var i = 0; i < kids.length; i++) {
      var r = kids[i].getBoundingClientRect();
      if (r.right > edge + 24) { cur = kids[i].dataset.proj || ''; break; }
    }
    if (cur === VPROJ_NOW) return;   /* vẫn dự án cũ → không đụng DOM */
    VPROJ_NOW = cur;
    var chips = vchips.querySelectorAll('.vchip');
    for (var j = 0; j < chips.length; j++) {
      var hit = !!cur && chips[j].textContent === cur;
      chips[j].classList.toggle('now', hit);
      if (!hit) continue;
      var cb = chips[j].getBoundingClientRect(), wb = vchips.getBoundingClientRect();
      if (cb.left < wb.left) vchips.scrollLeft -= (wb.left - cb.left) + 12;
      else if (cb.right > wb.right) vchips.scrollLeft += (cb.right - wb.right) + 12;
    }
  }
  function clearCurrentProject(){
    if (!vchips || VPROJ_NOW === null) return;
    VPROJ_NOW = null;
    vchips.querySelectorAll('.vchip.now').forEach(function(c){ c.classList.remove('now'); });
  }
  /* Trình sửa gọi sau khi đổi SITE.video: dựng lại danh sách + số đếm + ảnh nổi bật. */
  function refreshVideos(){ VLIST = buildVlist(); refreshVcount(); refreshHero(); renderVideos(); if (GH_DECK) GH_DECK.sync(); }
  ['Tất cả'].concat(P.map(function(p){ return p.n; })).forEach(function(label, i){
    var b = document.createElement('button');
    b.className = 'vchip'; b.textContent = label;
    b.setAttribute('aria-pressed', i === 0 ? 'true' : 'false');
    b.addEventListener('click', function(){
      vchips.querySelectorAll('.vchip').forEach(function(x){ x.setAttribute('aria-pressed','false'); });
      b.setAttribute('aria-pressed','true'); vProj = i === 0 ? 'all' : label; renderVideos();
      /* Đổi dự án: chồng thẻ cũ thu nhỏ + tan đi, chồng mới trồi lên. */
      if (GH_DECK) GH_DECK.filter();
    });
    vchips.appendChild(b);
  });
  document.getElementById('vsearch').addEventListener('input', function(e){ vQ = e.target.value.trim().toLowerCase(); renderVideos(); });
  renderVideos();

  /* ---------- CHỒNG HỒ SƠ VIDEO (chỉ điện thoại ≤720px) ----------
     Ý đồ: bỏ hẳn cảm giác "danh sách". Người xem cầm một CHỒNG thẻ 9:16, kéo
     thẻ trên cùng lên là nó bay đi, thẻ sau phóng to vào giữa, thẻ vừa bay
     chui xuống đáy chồng ⇒ vòng vô tận, không có điểm kết thúc để phải cuộn.

     Vì sao tự viết vật lý thay vì transition CSS: đặc tả yêu cầu lò xo
     (stiffness 180 / damping 26 / mass .95) và thẻ sau phải "nhúc nhích theo
     ngón tay" NGAY trong lúc kéo — transition chỉ chạy được từ A tới B nên
     không bám ngón tay được. Trang này là HTML tĩnh do máy chủ dựng, không có
     React/Framer Motion, nên lò xo viết tay trong một vòng rAF duy nhất: mọi
     thẻ dùng chung một khung hình, transform + filter chạy trên GPU.

     Chỉ có 3 thẻ trong chồng; thẻ thứ 4 trở đi KHÔNG dựng, chỉ nạp trước ảnh.
     Không bao giờ nạp trước video. */
  (function videoDeck(){
    var wrap = document.getElementById('vdeck'), stage = document.getElementById('vdstage'),
        dotsEl = document.getElementById('vddots'), hint = document.getElementById('vdhint'),
        sec = document.getElementById('video'), frame = wrap && wrap.querySelector('.vdframe'),
        elTitle = document.getElementById('vdtitle'), elProj = document.getElementById('vdproj'),
        elDur = document.getElementById('vddur'), elBy = document.getElementById('vdby');
    if (!wrap || !stage || !dotsEl || !sec || !frame) return;

    var MQ = window.matchMedia('(max-width:720px)');
    var RM = window.matchMedia('(prefers-reduced-motion:reduce)');
    /* KHÔNG dựng chồng ảnh khi template đang nằm trong iframe — đó là ô xem
       trước của trang sửa (/tai-khoan/website). Ở đó `EDITING` bằng false (chế
       độ Xem dựng bản y như khách nhận) nên deck vẫn bật, mà iframe lại hẹp
       hơn 720px ⇒ trên điện thoại nó chồng thêm ba lớp ảnh có blur lên một
       trang vốn đã nặng (React + antd + nguyên template + three.js), Safari
       iOS hết bộ nhớ và giết tab: "Đã có sự cố xảy ra liên tục".
       Ở ô xem trước cũng KHÔNG nên có deck: `touch-action:none` nuốt cử chỉ
       cuộn, còn chồng ảnh thì không sửa được gì. */
    var INFRAME = true;
    try { INFRAME = window.self !== window.top; } catch (e) { INFRAME = true; }
    var MAXV = 3;                          /* số thẻ dựng cùng lúc — cứng */
    /* Lò xo MỀM: tắt dần đúng tới hạn (zeta ~1 nên không nảy lại) nhưng chậm
       hơn bản đầu — anh Bình chốt 16/08 "mượt mà hơn, nhẹ nhàng hơn" sau khi xem
       bản chạy thật: 320/32/.8 lặng trong ~0.29s nên ảnh giật phắt một cái;
       180/26/.95 lặng trong ~0.42s, ảnh trôi đi thay vì bị búng đi. */
    var KS = 180, KC = 26, KM = 0.95;
    var PRE = {};                          /* ảnh đã xin nạp trước */

    /* Lò xo một chiều. `eps` là ngưỡng coi như đã đứng yên — tính theo ĐƠN VỊ
       của đại lượng (px cho độ dời, "nấc" cho vị trí trong chồng). */
    function Spring(x, eps){ this.x = x; this.t = x; this.v = 0; this.eps = eps; }
    Spring.prototype.set = function(x){ this.x = this.t = x; this.v = 0; };
    Spring.prototype.step = function(dt){
      this.v += ((-KS * (this.x - this.t) - KC * this.v) / KM) * dt;
      this.x += this.v * dt;
      if (Math.abs(this.x - this.t) < this.eps && Math.abs(this.v) < this.eps * 12) {
        this.x = this.t; this.v = 0; return true;
      }
      return false;
    };

    var D = { items: [], at: 0, cards: [], flying: [], on: false, h: 480,
              tw: new Spring(1, .004), phase: 'in',
              tx: new Spring(0, .4), dir: 0, hdrag: false,
              tf: new Spring(1, .004),     /* chữ trong khung nhoè một nhịp khi đổi nội dung */
              prev: null, sinking: [] };   /* ảnh trước đang được kéo trở lại · ảnh bị đẩy khỏi đáy chồng */
    var pool = [];                          /* thẻ đã bay xong, dùng lại cho thẻ mới */

    /* ---- dữ liệu ---- */
    function heroText(){
      var cap = document.getElementById('vherocap');
      var n0 = cap && cap.childNodes[0];
      var sp = cap && cap.querySelector('span');
      return { title: n0 && n0.nodeType === 3 ? n0.textContent.trim() : '',
               sub: sp ? sp.textContent.trim() : '' };
    }
    /* Video nổi bật đứng làm thẻ ĐẦU của chồng (chỉ khi đang xem "Tất cả"), nên
       khối "Mới nhất" bị giấu trên điện thoại vẫn không mất nội dung. */
    function items(){
      var out = [], h = (SITE.video && SITE.video.hero) || {}, hu = playUrl(h), ht;
      if (vProj === 'all' && hu) {
        ht = heroText();
        out.push({ tag: 'Mới nhất', proj: (P[0] && P[0].n) || '', img: coverImg(h.img),
                   tone: TONE_FALLBACK, title: ht.title || 'Video nổi bật', sub: ht.sub,
                   dur: h.dur || '', dev: '', url: hu, vert: !!coverImg(h.img) && isVertVideo(hu) });
      }
      VLIST.forEach(function(v){
        if (vProj !== 'all' && v.proj !== vProj) return;
        out.push({ tag: '', proj: v.proj, img: v.img, tone: v.tone,
                   title: v.title || v.proj || 'Video dự án', sub: v.sub,
                   dur: v.dur, dev: v.dev, url: v.url, vert: v.vert });
      });
      return out;
    }

    /* ---- ô ảnh ---- */
    var SHELL = '<span class="tg"></span>' +
      '<span class="pl"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>';

    function makeCard(item){
      var el = pool.pop();
      if (!el) { el = document.createElement('figure'); el.innerHTML = SHELL; }
      el.className = 'vdcard' + (item.vert ? ' vert' : '');
      el.style.opacity = '1'; el.style.filter = '';
      var tg = el.querySelector('.tg');
      tg.textContent = item.tag || ''; tg.style.display = item.tag ? '' : 'none';
      /* Ảnh chờ: nạp ngầm rồi mới dán vào — chưa xong thì để vân sáng chạy, KHÔNG
         để ô xám trơ hay vòng xoay. Ảnh hỏng thì rơi về nền gradient của dự án. */
      el.style.backgroundImage = '';
      el.setAttribute('data-load', '0');
      /* Clip dọc: ô ảnh vẫn 16:9 như mọi thẻ, ảnh nằm trọn ở giữa trên chính nó
         làm mờ — cùng lối với thẻ ở lưới máy tính (xem `.vid.vert`). */
      el.style.setProperty('--vimg', item.vert && item.img ? "url('" + String(item.img).replace(/'/g, '%27') + "')" : '');
      if (item.img) {
        var im = new Image();
        im.onload = function(){ el.style.backgroundImage = item.vert ? 'none' : bg({ img: item.img, tone: item.tone }); el.setAttribute('data-load', '1'); };
        im.onerror = function(){ el.style.backgroundImage = bg({ tone: item.tone || TONE_FALLBACK }); el.setAttribute('data-load', '1'); };
        im.src = item.img;
      } else {
        el.style.backgroundImage = bg({ tone: item.tone || TONE_FALLBACK });
        el.setAttribute('data-load', '1');
      }
      el.setAttribute('role', 'button');
      el.setAttribute('aria-label', 'Phát video ' + item.title);
      stage.appendChild(el);
      return { el: el, item: item, s: new Spring(0, .002), dy: new Spring(0, .4), drag: false, fly: false, pop: false };
    }

    /* Đổ chữ của ảnh đang ở trên cùng vào KHUNG cố định. `fade` = có nhoè một
       nhịp hay không (đổi do vuốt thì có, dựng lại cả chồng thì không). */
    function applyChrome(item, fade){
      item = item || {};
      elTitle.textContent = item.title || '';
      elProj.textContent = item.proj || item.sub || '';
      elProj.parentNode.style.display = (item.proj || item.sub) ? '' : 'none';
      elDur.textContent = item.dur || '';
      elBy.textContent = item.dev || item.sub || '';
      if (fade) { D.tf.set(0.15); D.tf.t = 1; }
    }
    function kill(c){ if (c.el.parentNode) c.el.parentNode.removeChild(c.el); if (pool.length < 4) pool.push(c.el); }

    /* ---- vẽ ----
       Một công thức DUY NHẤT cho cả chồng: `s` là vị trí liên tục trong chồng
       (0 = trên cùng). s=0/1/2 ra đúng các con số đặc tả (0/22/44px, 1/.95/.90,
       blur 0/4/8, sáng 100/90/80%), mà s ở giữa vẫn hợp lệ — nhờ vậy lúc kéo
       thẻ sau nhích lên được mượt thay vì nhảy nấc. */
    function paint(c){
      if (c.pop) return;
      var s = c.s.x, y = c.dy.x + 22 * s;
      c.el.style.transform = 'translate3d(0,' + y.toFixed(2) + 'px,0) scale(' + (1 - 0.05 * s).toFixed(4) + ')';
      if (!RM.matches) c.el.style.filter = 'blur(' + (4 * s).toFixed(2) + 'px) brightness(' + (1 - 0.1 * s).toFixed(3) + ')';
      c.el.style.zIndex = String(c.lead ? 70 : 60 - Math.round(s * 10));
      var op = 1;
      /* Ảnh bay lên phải tan HẲN trước khi trôi qua chữ tiêu đề của khung —
         khung đứng yên nên một tấm ảnh mờ lướt ngang chữ nhìn rất bẩn. */
      if (c.fly) op = Math.max(0, Math.min(1, (c.dy.x + D.h * 0.7) / (D.h * 0.45)));
      else if (s > 2) op = Math.max(0, 1 - (s - 2));
      c.el.style.opacity = op.toFixed(3);
    }
    function paintAll(){
      D.cards.forEach(paint); D.flying.forEach(paint); D.sinking.forEach(paint);
      if (D.prev) paint(D.prev);
    }
    function paintWrap(){
      var t = D.tw.x;
      frame.style.setProperty('--vd-tf', D.tf.x.toFixed(3));
      frame.style.setProperty('--vd-fade', t.toFixed(3));
      frame.style.setProperty('--vd-sc', (0.95 + 0.05 * t).toFixed(4));
      /* Đổi dự án bằng PILL: chồng mới trồi lên từ dưới. Đổi bằng VUỐT NGANG:
         chồng cũ bay theo chiều vuốt, chồng mới trượt vào từ phía đối diện —
         hướng đi phải khớp ngón tay, ngược lại là thấy sai ngay. */
      var k = (D.phase === 'in' ? -1 : 1) * D.dir;
      frame.style.setProperty('--vd-rise', (D.phase === 'in' && !D.dir ? 40 * (1 - t) : 0).toFixed(1) + 'px');
      frame.style.setProperty('--vd-x', (D.tx.x + k * 72 * (1 - t)).toFixed(1) + 'px');
    }
    /* Gỡ cờ "ảnh đầu" trên MỌI ảnh còn trong sân khấu rồi mới gắn lại cho ảnh
       trên cùng. Trước đây chỉ quét trong chồng nên ảnh vừa bay đi vẫn giữ cờ:
       nó còn `pointer-events:auto` + `touch-action:none` suốt lúc bay, vuốt
       liên tiếp cái thứ hai rơi trúng nó và trượt luôn. */
    function stamp(){
      var tops = stage.querySelectorAll('.vdcard.top'), i;
      for (i = 0; i < tops.length; i++) tops[i].classList.remove('top');
      if (D.cards[0]) D.cards[0].el.classList.add('top');
    }
    /* D.h = chiều cao THẬT của ô ảnh; mọi ngưỡng kéo, quãng bay và điểm tan
       đều tính theo nó. Phải đọc `.el.offsetHeight` — `D.cards[0]` là đối tượng
       thẻ, hỏi offsetHeight trên đó ra undefined nên D.h kẹt ở số dự phòng 480,
       kéo tay phải đi 144px mới sang được ảnh mới (đúng ra chỉ ~58px). */
    function measure(){ if (D.cards[0]) D.h = D.cards[0].el.offsetHeight || D.h; }
    function thr(){ return D.h * 0.3; }     /* kéo quá 30% chiều cao ảnh là sang ảnh mới */

    /* ---- vòng khung hình ---- */
    var raf = 0, last = 0;
    function run(){ wrap.classList.add('vdmoving'); if (!raf) { last = 0; raf = requestAnimationFrame(tick); } }
    function tick(ts){
      raf = 0;
      var dt = last ? Math.min((ts - last) / 1000, 1 / 30) : 1 / 60;
      last = ts;
      var busy = false, i, c;
      for (i = 0; i < D.cards.length; i++) {
        c = D.cards[i];
        if (!c.s.step(dt)) busy = true;
        if (c.drag) busy = true; else if (!c.dy.step(dt)) busy = true;
        paint(c);
      }
      for (i = D.flying.length - 1; i >= 0; i--) {
        c = D.flying[i];
        var done = c.dy.step(dt);
        paint(c);
        /* Bay khuất hẳn là dọn ngay, không đợi lò xo lặng — đỡ một quãng rỗng.
           Ảnh "trả về" (exit) thì phải đợi lò xo lặng vì nó đi ngược lên chỗ bị
           cắt, không có mốc khuất nào để bắt. */
        if (done || (c.fly && c.dy.x <= -(D.h + 60))) { kill(c); D.flying.splice(i, 1); }
        else busy = true;
      }
      /* Ảnh bị đẩy khỏi đáy chồng khi kéo ngược: chìm xuống nấc 3 rồi tan. */
      for (i = D.sinking.length - 1; i >= 0; i--) {
        c = D.sinking[i];
        if (c.s.step(dt)) { kill(c); D.sinking.splice(i, 1); }
        else { paint(c); busy = true; }
      }
      if (D.prev) {
        /* Chốt chặn: hết cử chỉ mà cờ "đang kéo" còn sót thì vòng khung hình
           chạy mãi. Không còn `g` nghĩa là ngón tay đã nhả — gỡ cờ. */
        if (D.prev.drag && !g) D.prev.drag = false;
        if (D.prev.drag) busy = true; else if (!D.prev.dy.step(dt)) busy = true;
        paint(D.prev);
      }
      if (!D.tw.step(dt)) busy = true;
      if (!D.tf.step(dt)) busy = true;
      if (D.hdrag) busy = true; else if (!D.tx.step(dt)) busy = true;
      if (D.phase === 'out' && D.tw.x === 0) { rebuild(); D.phase = 'in'; D.tw.t = 1; busy = true; }
      if (D.phase === 'in' && D.tw.x === 1 && D.dir) D.dir = 0;
      paintWrap();
      if (busy) raf = requestAnimationFrame(tick);
      else {
        last = 0;
        /* Hạ `will-change` khi mọi thứ đã đứng yên. Để cờ đó thường trực là ép
           iOS giữ luôn ba lớp GPU có blur — trang sửa nhúng nguyên template
           trong iframe nên đó là chỗ dễ hết bộ nhớ nhất. */
        wrap.classList.remove('vdmoving');
      }
    }

    /* ---- dựng lại cả chồng ---- */
    function rebuild(){
      D.cards.forEach(kill); D.flying.forEach(kill); D.sinking.forEach(kill);
      if (D.prev) kill(D.prev);
      D.cards = []; D.flying = []; D.sinking = []; D.prev = null;
      D.items = items(); D.at = 0;
      var n = D.items.length, vis = Math.min(n, MAXV), i;
      for (i = 0; i < vis; i++) {
        var c = makeCard(D.items[i]);
        c.s.set(i);
        D.cards.push(c);
      }
      applyChrome(D.items[0], false);
      stamp(); measure(); paintAll(); dots(); preload();
      /* Chỉ mách nước khi thật sự có chỗ để đi: một video và một dự án thì câu
         "kéo lên / vuốt ngang" là nói dối. */
      var many = n > 1, multi = chipList().length > 1;
      hint.textContent = many && multi ? 'Kéo ảnh lên/xuống để đổi video · vuốt ngang để đổi dự án'
                       : many ? 'Kéo ảnh lên xem video sau, kéo xuống quay lại'
                       : 'Vuốt ngang để đổi dự án';
      hint.style.display = (many || multi) ? '' : 'none';
    }

    /* ---- sang thẻ kế ---- */
    function advance(v0){
      var n = D.items.length;
      if (n < 2 || !D.cards.length) { snapBack(); return; }
      dropPrev();
      var front = D.cards.shift();
      front.drag = false; front.fly = true;
      front.dy.t = -(D.h + 60);
      /* Nối tiếp vận tốc ngón tay, nhưng không chậm hơn một mức tối thiểu để
         ảnh luôn đi hẳn kể cả khi người dùng kéo chậm rồi thả. Mức này cố ý để
         thấp (600 chứ không phải 900): ảnh rời đi nhẹ nhàng, không bị búng. */
      front.dy.v = Math.min(v0 || 0, -600);
      D.flying.push(front);
      D.at = (D.at + 1) % n;
      var vis = Math.min(n, MAXV), i;
      for (i = 0; i < D.cards.length; i++) D.cards[i].s.t = i;
      while (D.cards.length < vis) {
        var k = D.cards.length;
        var c = makeCard(D.items[(D.at + k) % n]);
        c.s.set(vis); c.s.t = k;          /* trồi lên từ đáy chồng */
        D.cards.push(c);
      }
      /* Khung không nhúc nhích — chỉ CHỮ trong khung đổi sang video mới. */
      applyChrome(D.items[D.at], true);
      stamp(); dots(); preload(); buzz(); run();
    }
    function snapBack(){
      if (D.cards[0]) { D.cards[0].dy.t = 0; D.cards[0].drag = false; }
      dropPrev(); peek(0); run();
    }

    /* ---- kéo XUỐNG: lấy lại ảnh vừa vuốt qua ----
       Ảnh đã đi thì nằm ở CUỐI chồng, không thể "kéo ngược nó lên" được. Nên
       lúc ngón tay đi xuống, dựng sẵn ảnh trước đó ở NGOÀI mép trên (chỗ đang
       bị cắt) rồi cho nó bám ngón tay đi xuống — đúng cảm giác lôi tờ vừa lật
       trở lại. Chồng bên dưới đứng im, không nhúc nhích theo. */
    function mkPrev(){
      var n = D.items.length;
      if (n < 2 || D.prev) return D.prev;
      var c = makeCard(D.items[(D.at - 1 + n) % n]);
      c.lead = true; c.drag = true;
      c.s.set(0); c.dy.set(-(D.h + 24));
      D.prev = c;
      return c;
    }
    /* Bỏ ý định kéo ngược: ảnh trượt trả về chỗ khuất rồi tự dọn. */
    function dropPrev(){
      var c = D.prev;
      if (!c) return;
      D.prev = null;
      c.drag = false; c.lead = false; c.exit = true;
      c.dy.t = -(D.h + 24);
      D.flying.push(c);
    }
    function retreat(){
      var n = D.items.length, c = D.prev;
      if (n < 2 || !c) { snapBack(); return; }
      D.prev = null;
      c.drag = false; c.lead = false; c.dy.t = 0;
      D.at = (D.at - 1 + n) % n;
      D.cards.unshift(c);
      var vis = Math.min(n, MAXV), i;
      for (i = 0; i < D.cards.length; i++) D.cards[i].s.t = i;
      /* Chồng chỉ chứa `vis` ảnh: ảnh dôi ra ở đáy chìm xuống nấc 3 rồi tan,
         KHÔNG xoá phắt — chỗ đó vẫn ló ra một vệt nên xoá phắt là thấy giật. */
      while (D.cards.length > vis) { var last = D.cards.pop(); last.s.t = 3.2; D.sinking.push(last); }
      applyChrome(D.items[D.at], true);
      stamp(); dots(); preload(); buzz(); run();
    }
    /* Ngay khi ngón tay chạm và kéo, các thẻ sau đã nhích lên/ nét lại — cảm
       giác "thẻ phía sau muốn đi lên". Chỉ đặt ĐÍCH, để lò xo tự đuổi theo. */
    function peek(p){
      for (var i = 1; i < D.cards.length; i++) D.cards[i].s.t = i - 0.35 * p;
    }
    function buzz(){ try { if (navigator.vibrate) navigator.vibrate(10); } catch (e) {} }

    function dots(){
      var n = D.items.length, k = Math.min(n, 5), i;
      if (dotsEl.childNodes.length !== k) {
        dotsEl.innerHTML = '';
        for (i = 0; i < k; i++) dotsEl.appendChild(document.createElement('i'));
      }
      var act = n > 1 ? Math.round(D.at / (n - 1) * (k - 1)) : 0;
      for (i = 0; i < k; i++) dotsEl.childNodes[i].className = i <= act ? 'on' : '';
      dotsEl.style.display = n > 1 ? '' : 'none';
    }
    /* Nạp trước ẢNH của hai thẻ chưa dựng kế tiếp. Video thì không — mở thẻ mới
       tải, đúng đặc tả (và đỡ tốn 3G của khách). */
    function preload(){
      var n = D.items.length;
      for (var k = 0; k < 2; k++) {
        var it = D.items[(D.at + MAXV + k) % n];
        if (it && it.img && !PRE[it.img]) { PRE[it.img] = 1; (new Image()).src = it.img; }
      }
    }

    /* ---- mở video: phóng nhẹ rồi vào TOÀN MÀN HÌNH (ghCinePlay), không popup ---- */
    function openCard(c){
      var it = c.item;
      c.pop = true;
      c.el.style.transform = 'translate3d(0,0,0) scale(1.05)';
      setTimeout(function(){ c.pop = false; paint(c); ghCinePlay(it.url, it.title); }, 110);
    }

    /* ---- đổi dự án (vuốt ngang / bấm pill) ----
       Vuốt ngang lái chính hàng pill có sẵn: bấm đúng cái pill kế tiếp để bộ
       lọc, số đếm và lưới máy tính cùng đổi theo — không đẻ ra một đường thay
       đổi bộ lọc thứ hai. Pill của dự án KHÔNG có video thì nhảy qua, vì dừng
       ở đó là chồng thẻ trống trơ. */
    function chipList(){ return vchips ? vchips.querySelectorAll('.vchip') : []; }
    function chipHasVideo(chips, i){
      if (i === 0) return true;                       /* "Tất cả" */
      var label = chips[i].textContent;
      for (var k = 0; k < VLIST.length; k++) if (VLIST[k].proj === label) return true;
      return false;
    }
    function switchProject(delta){
      var chips = chipList();
      if (chips.length < 2) return false;
      var cur = 0, i;
      for (i = 0; i < chips.length; i++) if (chips[i].getAttribute('aria-pressed') === 'true') cur = i;
      var next = cur;
      for (i = 1; i <= chips.length; i++) {
        var cand = ((cur + delta * i) % chips.length + chips.length) % chips.length;
        if (chipHasVideo(chips, cand)) { next = cand; break; }
      }
      if (next === cur) return false;
      /* Vuốt sang TRÁI (delta = +1) ⇒ chồng cũ bay sang trái, chồng mới vào từ phải. */
      D.dir = delta > 0 ? -1 : 1;
      chips[next].click();
      if (chips[next].scrollIntoView) chips[next].scrollIntoView({ block: 'nearest', inline: 'center' });
      buzz();
      return true;
    }

    /* ---- cử chỉ: dọc = video kế · ngang = dự án khác ----
       Khoá trục ngay lần dịch đầu tiên (>8px): nửa chừng mà đổi trục thì thẻ
       vừa nhấc vừa trượt, rối tay. */
    var g = null;
    stage.addEventListener('pointerdown', function(e){
      if (!D.on || !D.cards.length) return;
      var c = D.cards[0];
      if (!c.el.contains(e.target)) return;
      var now = performance.now();
      g = { id: e.pointerId, y0: e.clientY, x0: e.clientX, t0: now,
            ly: e.clientY, lx: e.clientX, lt: now, v: 0, vx: 0, moved: 0, ax: '' };
      c.dy.v = 0; D.tx.v = 0;
      try { c.el.setPointerCapture(e.pointerId); } catch (err) {}
      run();
    });
    stage.addEventListener('pointermove', function(e){
      if (!g || e.pointerId !== g.id || !D.cards.length) return;
      var c = D.cards[0], dy = e.clientY - g.y0, dx = e.clientX - g.x0, now = performance.now();
      g.moved = Math.max(g.moved, Math.abs(dy), Math.abs(dx));
      if (now > g.lt) {
        g.v = (e.clientY - g.ly) / (now - g.lt) * 1000;
        g.vx = (e.clientX - g.lx) / (now - g.lt) * 1000;
        g.ly = e.clientY; g.lx = e.clientX; g.lt = now;
      }
      if (!g.ax && g.moved > 8) {
        g.ax = Math.abs(dx) > Math.abs(dy) ? 'h' : 'v';
        if (g.ax === 'v') c.drag = true; else D.hdrag = true;
      }
      if (g.ax === 'h') {
        /* Cả chồng đi theo ngón tay, có níu lại để thấy rõ đây là cử chỉ "đổi
           dự án" chứ không phải kéo tự do. */
        D.tx.x = dx * 0.55; D.tx.t = 0;
      } else if (g.ax === 'v') {
        if (dy > 6) {
          /* Xuống = lôi ảnh trước trở lại. Chồng hiện tại ĐỨNG YÊN, chỉ ảnh
             mới thò xuống theo ngón tay. Chỉ dựng khi ngón tay đã đi ĐỦ XA:
             dựng từ 1px thì mỗi cú chạm cũng đẻ ra một thẻ thừa. */
          var p = mkPrev();
          if (p) { p.dy.x = -(D.h + 24) + dy; p.drag = true; }
          c.dy.x = 0; c.dy.t = 0; peek(0);
        } else {
          /* Đổi chiều giữa chừng: trả ảnh trước về chỗ khuất. */
          if (D.prev) dropPrev();
          c.dy.x = dy; c.dy.t = 0;
          peek(Math.min(1, -dy / thr()));
        }
      }
      paintAll(); paintWrap();
    });
    function endDrag(e){
      if (!g || (e && e.pointerId !== g.id)) return;
      var gg = g, c = D.cards[0];
      g = null; D.hdrag = false;
      if (!c) return;
      c.drag = false;
      /* Chạm gọn một cái (gần như không xê dịch) = mở video, không phải kéo. */
      if (gg.moved < 9 && performance.now() - gg.t0 < 420) {
        /* Trả ảnh trước về TRƯỚC khi mở: bỏ quên nó ở trạng thái "đang kéo" là
           vòng khung hình không bao giờ dừng (cờ bận luôn bật) — CPU quay mãi. */
        dropPrev(); c.dy.set(0); peek(0); openCard(c); run(); return;
      }
      if (gg.ax === 'h') {
        var far = Math.abs(D.tx.x) > 44 || Math.abs(gg.vx) > 520;
        D.tx.t = 0;
        if (!far || !switchProject(D.tx.x < 0 ? 1 : -1)) run();
        return;
      }
      if (D.prev) {
        /* Quãng ngón tay đã kéo xuống = độ dời của ảnh trước so với chỗ khuất. */
        D.prev.drag = false;
        /* Búng nhanh cũng phải đi được một quãng tối thiểu: vận tốc đo trên
           MỘT khung hình nên một cú chạm rê 2-3px có thể ra số rất to, đủ để
           đổi video ngoài ý muốn. */
        var back = D.prev.dy.x + D.h + 24;
        if (back > thr() || (back > 26 && gg.v > 450)) retreat();
        else { dropPrev(); run(); }
        return;
      }
      var upBy = -c.dy.x;
      if (upBy > thr() || (upBy > 26 && gg.v < -450)) advance(gg.v);
      else snapBack();
    }
    stage.addEventListener('pointerup', endDrag);
    stage.addEventListener('pointercancel', endDrag);
    /* Dòng chân nằm NGOÀI chồng ảnh nên không dính cử chỉ — phải tự nối vào,
       nếu không bấm đúng chữ "Xem video" lại là chỗ duy nhất không phát được. */
    frame.querySelector('.vdf').addEventListener('click', function(){
      if (D.on && D.cards.length) openCard(D.cards[0]);
    });
    /* Bàn phím: mở bằng Enter/Space, mũi tên dọc sang video, mũi tên ngang đổi dự án. */
    stage.addEventListener('keydown', function(e){
      if (!D.on || !D.cards.length) return;
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openCard(D.cards[0]); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); advance(-1200); }
      else if (e.key === 'ArrowDown') { e.preventDefault(); if (mkPrev()) { D.prev.drag = false; retreat(); } }
      else if (e.key === 'ArrowLeft') { e.preventDefault(); switchProject(-1); }
      else if (e.key === 'ArrowRight') { e.preventDefault(); switchProject(1); }
    });

    /* ---- bật/tắt theo bề ngang màn hình ---- */
    function sync(){
      var want = MQ.matches && !EDITING && !INFRAME && items().length > 0;
      if (want !== D.on) {
        D.on = want;
        wrap.classList.toggle('on', want);
        sec.classList.toggle('deckon', want);
        if (!want) { D.cards.forEach(kill); D.flying.forEach(kill); D.cards = []; D.flying = []; return; }
        D.phase = 'in'; D.tw.set(1);
      }
      if (!want) return;
      rebuild(); paintWrap(); run();
    }
    /* Đổi dự án: chồng cũ thu nhỏ + tan, dựng lại rồi chồng mới trồi lên. */
    function filter(){
      if (!D.on) { sync(); return; }
      if (!items().length) { sync(); return; }
      D.phase = 'out'; D.tw.t = 0; run();
    }
    GH_DECK = { sync: sync, filter: filter };

    if (MQ.addEventListener) MQ.addEventListener('change', sync);
    else if (MQ.addListener) MQ.addListener(sync);
    var rz;
    window.addEventListener('resize', function(){
      if (!D.on) return;
      clearTimeout(rz);
      rz = setTimeout(function(){ measure(); paintAll(); }, 120);
    });
    sync();
  })();

  /* ---------- nhật ký khách hàng: dải ảnh mở ra theo cuộn ---------- */
  var DIARY = (SITE.diary && SITE.diary.items) || [];

  /**
   * Bề ngang ảnh nhật ký, hạ thêm một nấc trên điện thoại.
   *
   * Đây là vùng DUY NHẤT của trang nạp vài chục ảnh cùng lúc, mà chúng nằm
   * trong bộ nhớ ở dạng ĐÃ GIẢI NÉN — không phải cỡ tệp tải về. Máy chủ đã trả
   * sẵn bản 960px (2,3 MB/tấm); trên màn ~390px thì 640px là dư, mà chỉ còn
   * 1,0 MB/tấm. Nhân với vài chục tấm là khác nhau vài trăm MB — đúng khoảng
   * làm Safari trên iPhone chạm trần bộ nhớ tab rồi giết trang. Dải ảnh mới còn
   * lặp mỗi ảnh vài lượt nên KHÔNG được bỏ bước này.
   */
  var DIARY_W = window.matchMedia('(max-width: 767px)').matches ? 640 : 960;
  function diaryImg(url){
    // Chỉ đụng ảnh đi qua ống dẫn — ảnh sẵn có của mẫu là đường dẫn tĩnh, gắn
    // `&w=` vào là thành 404.
    return typeof url === 'string' && url.indexOf('/landing-assets/anh?') === 0
      ? url.replace(/&w=\d+$/, '&w=' + DIARY_W)
      : url;
  }

  (function unfurl(){
    var wrap = document.getElementById('unfwrap');
    var banner = document.getElementById('unfbanner');
    var mat = document.getElementById('unfmat');
    if (!wrap || !banner || !mat) return;
    var cols = [].slice.call(mat.querySelectorAll('.unf-col'));
    var word1 = document.getElementById('unfword1'), word2 = document.getElementById('unfword2');
    /* Mục nhật ký chưa gắn ảnh thì bỏ qua — lọt vào là dán <img src=""> vào cột. */
    var imgs = DIARY.map(function(d){ return d.img; }).filter(Boolean).map(diaryImg);
    if (!imgs.length) { wrap.classList.add('empty'); return; }

    /* Mỗi cột nhận ảnh so le (0,4,8… | 1,5,9… ). Ít ảnh quá thì lặp cho đủ 3 thẻ
       — để cột rỗng là thủng một khoảng đen giữa dải. */
    var bases = cols.map(function(_, ci){
      var base = imgs.filter(function(_, i){ return i % 4 === ci; });
      if (!base.length) base = [imgs[ci % imgs.length]];
      while (base.length < 3) base = base.concat(base);
      return base;
    });
    function addRound(col, base){
      base.forEach(function(src){
        var card = document.createElement('div');
        card.className = 'unf-card';
        var im = document.createElement('img');
        im.src = src; im.alt = ''; im.loading = 'lazy'; im.decoding = 'async';
        card.appendChild(im);
        col.appendChild(card);
      });
    }
    cols.forEach(function(col, ci){ addRound(col, bases[ci]); addRound(col, bases[ci]); });

    /* innerHeight thay cho 100vh để trên điện thoại khung không bị thanh địa chỉ
       ăn mất một khúc. */
    var VH = 0, MAT = 0, TAIL = 0;

    /* Quãng trôi của mỗi cột tính theo CHIỀU CAO KHUNG ẢNH (.unf-mat = 150vh),
       KHÔNG theo chiều cao cột. Nếu tính theo % chiều cao cột thì cột càng dài
       quãng trôi càng dài ⇒ nối thêm ảnh bao nhiêu cũng vẫn hụt, cuối khối hở
       một mảng đen. Cột 2 và 4 chạy ngược pha với 1 và 3 — đó là thứ tạo cảm
       giác dải ảnh "mở ra" chứ không phải cả mảng trôi cùng một chiều. */
    var COL_Y = [[0, -0.40], [-0.40, 0.10], [0, -0.40], [-0.30, 0.20]];

    function measure(){
      VH = window.innerHeight;
      MAT = mat.getBoundingClientRect().height || VH * 1.5;
      /* Quãng "chạy thêm" cuối khối = ĐÚNG 2 hàng ảnh (anh Bình chốt): ảnh phẳng
         ra rồi vẫn còn hai hàng nữa trôi qua mới nhả trang đi tiếp. Đo bằng
         khoảng cách hai thẻ thật nên đổi cỡ màn (thẻ 200/300/400px, khe 16/24px)
         là tự khớp. */
      var first = cols[0].children[0], second = cols[0].children[1];
      TAIL = first && second ? 2 * (second.offsetTop - first.offsetTop) : 0;
      /* Cột phải dài hơn: khung + 2 lần quãng trôi xa nhất. Thiếu là mép trên
         (hoặc mép dưới) của cột lọt vào khung, thành một khoảng đen. */
      var far = 0;
      COL_Y.forEach(function(y){ far = Math.max(far, Math.abs(y[0]), Math.abs(y[1])); });
      var need = MAT + 2 * (far * MAT + TAIL) + 40;
      cols.forEach(function(col, ci){
        var guard = 0;
        while (col.scrollHeight < need && guard++ < 8) addRound(col, bases[ci]);
      });
    }
    measure();

    function clamp01(v){ return v < 0 ? 0 : v > 1 ? 1 : v; }
    function seg(p, a, b){ return clamp01((p - a) / (b - a)); }
    function mix(a, b, t){ return a + (b - a) * t; }

    /* Ba chặng nối nhau trên cùng một trục cuộn:
         0    → 0.12  khung nở ra full màn
         0.12 → 0.66  dải ảnh dựng thẳng + phẳng hẳn ra
         0.66 → 1     ĐÃ phẳng, chỉ còn ảnh trôi thêm 2 hàng rồi mới nhả trang.
       Hai chữ mờ đi trong khoảng 0.12–0.55, đúng lúc ảnh phóng lên phủ dần. */
    var P_BANNER = 0.12, P_FLAT = 0.66;

    function paint(p){
      var t1 = seg(p, 0, P_BANNER);          // khung nở ra
      var t2 = seg(p, P_BANNER, P_FLAT);     // ma trận ảnh dựng thẳng lên
      var t3 = seg(p, P_FLAT, 1);            // hai hàng chạy thêm
      var tw = seg(p, P_BANNER, 0.55);       // hai chữ chìm đi
      /* Khung tràn ngang sẵn nên chỉ còn chiều cao mở ra; bề ngang để CSS lo
         (width:100%) — set bằng px ở đây là lệch một hai điểm ảnh với mép màn. */
      banner.style.height = (VH * mix(0.80, 1, t1)).toFixed(1) + 'px';
      mat.style.transform =
        'translate3d(0,0,' + mix(-800, 0, t2).toFixed(1) + 'px)' +
        ' rotateX(' + mix(25, 4, t2).toFixed(2) + 'deg)' +
        ' rotateY(' + mix(-45, -8, t2).toFixed(2) + 'deg)' +
        ' rotateZ(' + mix(15, 2, t2).toFixed(2) + 'deg)';
      for (var i = 0; i < cols.length; i++) {
        var y = COL_Y[i] || COL_Y[0];
        /* Chạy thêm thì đi TIẾP đúng chiều cột đang đi. */
        var tail = (y[1] >= y[0] ? 1 : -1) * TAIL * t3;
        cols[i].style.transform = 'translate3d(0,' + (mix(y[0], y[1], t2) * MAT + tail).toFixed(1) + 'px,0)';
      }
      var fade = (1 - tw).toFixed(3);
      word1.style.opacity = fade; word2.style.opacity = fade;
      word1.style.setProperty('--wy', (-16 * tw).toFixed(1) + 'px');
      word2.style.setProperty('--wy', (16 * tw).toFixed(1) + 'px');
    }

    /* Tiến độ cuộn của riêng khối này: 0 lúc mép trên chạm đỉnh màn, 1 lúc mép
       dưới chạm đáy màn (đúng offset ["start start","end end"] của bản gốc). */
    function target(){
      var r = wrap.getBoundingClientRect();
      var run = r.height - VH;
      return run <= 0 ? 0 : clamp01(-r.top / run);
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      wrap.classList.add('flat');
      return;
    }

    /* MỘT lò xo cho tất cả (stiffness 100, damping 20, mass 0.5 — bộ số của bản
       gốc, ζ≈1.4 nên tới đích là dừng, không nảy). Bám thẳng scrollY là ảnh
       giật theo từng nấc lăn chuột. Vòng lặp tự tắt khi lò xo đã nghỉ, cuộn tới
       đâu mới bật lại — không đốt rAF suốt trang. */
    var K = 100, C = 20, M = 0.5;
    var x = target(), v = 0, raf = 0, last = 0, running = false;
    paint(x);

    function frame(now){
      var dt = last ? Math.min((now - last) / 1000, 1 / 30) : 1 / 60;
      last = now;
      var g = target();
      v += ((K * (g - x) - C * v) / M) * dt;
      x += v * dt;
      if (Math.abs(g - x) < 0.0002 && Math.abs(v) < 0.0008) {
        x = g; v = 0; paint(x); raf = 0; running = false; return;
      }
      paint(x);
      raf = requestAnimationFrame(frame);
    }
    /* Huỷ khung đang chờ rồi xin khung mới, KHÔNG chỉ `if (!raf)`: trang mở ở
       tab nền có thể ôm một mã rAF không bao giờ chạy, cờ `raf` kẹt khác 0 và
       kick() im lặng bỏ qua mãi mãi — khối đứng chết ở khung hình đầu. Đã dính
       đúng lỗi này khi khung xem trước bị ẩn (18/08). */
    function kick(){
      if (raf) cancelAnimationFrame(raf);
      if (!running) last = 0;
      running = true;
      raf = requestAnimationFrame(frame);
    }

    window.addEventListener('scroll', kick, { passive: true });
    window.addEventListener('resize', function(){ measure(); kick(); });
    document.addEventListener('visibilitychange', function(){ if (!document.hidden) kick(); });
    /* Ảnh vào muộn có thể làm cột dài ra ⇒ đo lại rồi vẽ một nhịp cho khớp. */
    window.addEventListener('load', function(){ measure(); kick(); });
  })();

  /* ---------- chọn dự án trong form + chân trang ---------- */
  var fsel = document.getElementById('fproject');
  P.forEach(function(p){ var o = document.createElement('option'); o.textContent = p.n; fsel.appendChild(o); });
  var oOther = document.createElement('option'); oOther.textContent = 'Chưa chọn được — nhờ anh tư vấn'; fsel.appendChild(oOther);

  /* Panel chọn dự án tự dựng (xem chú thích ở .selpop): <select> giữ giá trị,
     panel chỉ là lớp hiển thị — dựng lại từ fsel.options mỗi lần mở nên danh
     sách dự án có đổi cũng không lệch. */
  (function customSelect() {
    var fld = fsel.closest('.fld');
    if (!fld) return;
    var pop = document.createElement('div');
    pop.className = 'selpop';
    pop.setAttribute('role', 'listbox');
    pop.setAttribute('aria-label', 'Dự án quan tâm');
    fld.appendChild(pop);

    var TICK = '<span class="tick"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.4" style="width:16px;height:16px"><path d="M4 10.5l4 4 8-9"/></svg></span>';
    function close() { fld.classList.remove('open'); }
    function build() {
      pop.innerHTML = '';
      Array.prototype.forEach.call(fsel.options, function (o, i) {
        /* Dòng đầu "-- Dự án quan tâm --" chỉ là placeholder của Ô, không đưa
           vào panel — panel CHỈ liệt kê tên dự án (anh Bình chốt 15/08). */
        if (i === 0) return;
        var b = document.createElement('button');
        b.type = 'button';
        b.className = i === fsel.selectedIndex ? 'on' : '';
        b.setAttribute('role', 'option');
        b.setAttribute('aria-selected', i === fsel.selectedIndex ? 'true' : 'false');
        b.innerHTML = TICK;
        b.appendChild(document.createTextNode(o.textContent));
        b.addEventListener('click', function () {
          fsel.selectedIndex = i;
          /* Khách đã tự tay chọn → băng chuyền dự án không được ghi đè nữa. */
          fsel.dataset.userPicked = '1';
          fsel.dispatchEvent(new Event('change', { bubbles: true }));
          close();
        });
        pop.appendChild(b);
      });
    }
    function toggle() {
      if (fld.classList.contains('open')) { close(); return; }
      build();
      fld.classList.add('open');
    }
    /* mousedown (không phải click) mới chặn được popup gốc của trình duyệt. */
    fsel.addEventListener('mousedown', function (e) { e.preventDefault(); toggle(); fsel.focus(); });
    fsel.addEventListener('keydown', function (e) {
      if (e.key === ' ' || e.key === 'Enter' || e.key === 'ArrowDown' || e.key === 'ArrowUp') { e.preventDefault(); toggle(); }
      if (e.key === 'Escape') close();
    });
    document.addEventListener('pointerdown', function (e) { if (!fld.contains(e.target)) close(); });
  })();

  /* ---------- dự án: coverflow Sổ tay ---------- */
  var stageEl = document.getElementById('cfstage'), dotsEl = document.getElementById('cfdots');
  var list = P.slice(), cur = 0, nodes = [];
  /* Vừa vuốt xong thì cú click sinh ra sau đó KHÔNG được mở dự án — vuốt để
     xem tiếp mà bị nhảy sang trang chi tiết là hỏng cả thao tác. */
  var swipedAt = 0;

  function build(){
    stageEl.innerHTML = ''; dotsEl.innerHTML = ''; nodes = [];
    list.forEach(function(p,i){
      var d = document.createElement('div');
      d.className = 'cf-card';
      d.innerHTML =
        '<div class="cf-media" style="background-image:' + bg(p) + '"></div>' +
        '<div class="cf-grad"></div>' +
        '<div class="cf-pname">' + p.n + '</div>';
      d.addEventListener('click', function(e){
        /* Thẻ giữa mở NGAY trang chia sẻ dự án trong giao diện hiện tại (không
           tab mới) — Back của trình duyệt/SPA đưa về đúng chỗ đang đứng. */
        if ((e.timeStamp || 0) - swipedAt < 400) return;
        if (i === cur) openProject(p);
        else go(i);
      });
      stageEl.appendChild(d); nodes.push(d);

      var dot = document.createElement('button');
      dot.className = 'cf-dot'; dot.setAttribute('aria-label', p.n);
      dot.addEventListener('click', function(){ go(i); });
      dotsEl.appendChild(dot);
    });
    layout();
  }

  function layout(){
    var n = list.length;
    nodes.forEach(function(d,i){
      var off = i - cur;
      if (off > n/2) off -= n;
      if (off < -n/2) off += n;
      var ab = Math.abs(off), sg = off === 0 ? 0 : (off > 0 ? 1 : -1);
      d.style.transform = 'translate(-50%,-50%) translateX(' + (off*60) + '%) translateZ(' + (-ab*180) + 'px) rotateY(' + (-sg*36) + 'deg) scale(' + (off === 0 ? 1 : .8) + ')';
      d.style.zIndex = 100 - ab;
      d.style.opacity = ab > 2.4 ? 0 : 1;
      d.style.pointerEvents = ab > 2.4 ? 'none' : 'auto';
      d.classList.toggle('active', off === 0);
    });
    [].forEach.call(dotsEl.children, function(x,i){ x.classList.toggle('on', i === cur); });
    var p = list[cur]; if (!p) return;
    document.getElementById('cfname').textContent = p.n;
    /* Băng chuyền chỉ được GỢI Ý dự án vào form khi khách CHƯA tự chọn — trước
       đây nó ghi đè cả lựa chọn tay (và cả lúc panel chọn đang mở) nên ô chọn
       với hàng tick lệch nhau, nhìn như "bị giật". */
    var selFld = fsel ? fsel.closest('.fld') : null;
    if (fsel && !fsel.dataset.userPicked && !(selFld && selFld.classList.contains('open'))) fsel.value = p.n;
  }
  function go(i){ if (!list.length) return; cur = (i + list.length) % list.length; layout(); }
  document.querySelector('.cfprev').addEventListener('click', function(){ go(cur-1); });
  document.querySelector('.cfnext').addEventListener('click', function(){ go(cur+1); });
  /* VUỐT NGANG (điện thoại) + LĂN CHUỘT/TRACKPAD (máy tính).
     Bản cũ chỉ so hai điểm đầu–cuối nên: vuốt chậm hay vuốt xong nhấc tay
     ngoài khung là mất; vuốt dọc để cuộn trang cũng bị tính là đổi thẻ. Nay
     bám theo cả quãng đường: cú nào NGANG nhiều hơn DỌC mới ăn, và đổi thẻ
     ngay khi vượt ngưỡng chứ không đợi nhấc tay. */
  (function swipe(){
    var x0 = null, y0 = null, fired = false, axis = '', held = false;
    var THRESH = 46;
    /* KHÔNG được giữ con trỏ (setPointerCapture) ngay ở pointerdown: trình
       duyệt dời luôn cả mouseup/click sang phần tử đang giữ, mà ở đây phần tử
       đó là .cf-stage — thẻ dự án nằm BÊN TRONG nó nên cú bấm chuột không bao
       giờ tới được tay bấm của thẻ ⇒ bấm thẻ giữa trên MÁY TÍNH không mở trang
       dự án nữa (điện thoại vẫn chạy vì nhánh 'touch' không giữ con trỏ). Chỉ
       giữ con trỏ SAU KHI đã chốt đây là cú vuốt ngang thật (xem pointermove). */
    stageEl.addEventListener('pointerdown', function(e){
      x0 = e.clientX; y0 = e.clientY; fired = false; axis = ''; held = false;
    });
    stageEl.addEventListener('pointermove', function(e){
      if (x0 === null || fired) return;
      var dx = e.clientX - x0, dy = e.clientY - y0;
      /* Chốt trục ở cú nhích đầu tiên rồi giữ nguyên — nếu để so lại mỗi lần
         thì tay hơi chệch một nhịp là cử chỉ nhảy trục, vừa cuộn vừa đổi thẻ. */
      if (!axis && (Math.abs(dx) > 8 || Math.abs(dy) > 8)) axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
      if (axis !== 'x') return;
      if (Math.abs(dx) > THRESH) {
        go(cur + (dx < 0 ? 1 : -1));
        fired = true;
        swipedAt = e.timeStamp || 0;
        /* Đã đổi thẻ rồi mới giữ con trỏ: phần đuôi cử chỉ (kể cả khi tay đi ra
           ngoài khung) vẫn về đúng dải, và cú click sinh ra sau cú vuốt bị dời
           lên .cf-stage nên chắc chắn không mở nhầm trang dự án. */
        if (!held && e.pointerType !== 'touch' && stageEl.setPointerCapture) {
          held = true;
          try { stageEl.setPointerCapture(e.pointerId); } catch (err) {}
        }
      }
    });
    var end = function(){ x0 = null; y0 = null; axis = ''; held = false; };
    stageEl.addEventListener('pointerup', end);
    stageEl.addEventListener('pointercancel', end);
    window.addEventListener('pointerup', end);

    /* Lăn chuột / vuốt hai ngón trên trackpad. CHỈ nhận cú NGANG (deltaX) hoặc
       Shift+lăn: cướp cả cú lăn dọc thì người xem mắc kẹt ở khối dự án, không
       xuống nổi phần dưới trang. */
    var lock = 0;
    stageEl.addEventListener('wheel', function(e){
      var dx = e.shiftKey ? e.deltaY : e.deltaX;
      if (Math.abs(dx) < 8 || Math.abs(dx) < Math.abs(e.shiftKey ? 0 : e.deltaY)) return;
      e.preventDefault();
      var now = e.timeStamp || 0;
      if (now - lock < 260) return; // một cú vuốt trackpad bắn hàng chục sự kiện
      lock = now;
      go(cur + (dx > 0 ? 1 : -1));
    }, { passive: false });
  })();
  build();

  /* ---------- dải thẻ CHẠY NGANG THEO CUỘN (dùng chung) ----------
     Anh Bình chốt 16/08, thay hẳn lối tự trôi vô cực: dải thẻ dính lại giữa
     khung nhìn, cuộn chuột / vuốt bao nhiêu thì thẻ đi ngang bấy nhiêu; hết
     thẻ cuối là nhả ra, trang cuộn tiếp đúng hướng đang cuộn.

     Cách làm: khối bọc .pinrail được kéo cao thêm ĐÚNG bằng quãng đường ngang
     (1px cuộn = 1px ngang), lớp .pinrail-in dính (sticky) lại, còn dải thẻ chỉ
     việc dịch translateX theo phần trăm đã cuộn qua khối bọc.

     KHÔNG bắt sự kiện wheel/touch — chỉ đọc vị trí cuộn. Bắt sự kiện là cướp
     cú lăn của người xem: cuộn nhanh thì kẹt, trên điện thoại thì mất đà và
     cuộn ngược lên hay bị nhảy. Đọc vị trí thì mọi cách cuộn (lăn chuột, vuốt
     trackpad, vuốt tay, kéo thanh cuộn, Page Down) đều chạy đúng như nhau. */
  /* Hệ số truyền + bám đuổi (anh Bình chốt 17/08, sau khi xem bản chạy thật
     trên máy tính: "thẻ nhảy từng nấc, giật").

     GEAR — 1px cuộn KHÔNG còn = 1px ngang nữa. Một nấc lăn chuột trên Windows
     nhảy ~100px một phát, ánh xạ 1:1 thì thẻ dịch đúng 100px trong MỘT khung
     hình: mắt không thấy chuyển động, chỉ thấy thẻ đứng ở chỗ khác. Hạ còn
     0.6 thì cùng nấc đó thẻ chỉ đi ~60px — quãng đường ngang giữ NGUYÊN, chỉ
     trải trên đoạn cuộn dài hơn, nên chuyển động thưa và êm hơn hẳn.

     EASE — thời gian hồi (giây) của lối bám đuổi: thẻ không nhảy thẳng tới vị
     trí cuộn mà trượt tới. Đủ để nuốt cái nhảy nấc của bánh xe, chưa đủ để
     thấy trễ tay.

     CẢ HAI CHỈ ÁP CHO CHUỘT/TRACKPAD. Ngón tay cuộn là dòng liên tục, vốn đã
     mượt sẵn: hạ hệ số ở đó chỉ làm khối dài thêm (vuốt mỏi tay), còn bám đuổi
     thì thành trễ — thẻ không dính ngón. Điện thoại giữ nguyên 1:1, tức thì. */
  var PIN_FINE = !matchMedia('(pointer:coarse)').matches;
  var PIN_GEAR = PIN_FINE ? 0.6 : 1;
  var PIN_EASE = PIN_FINE ? 0.11 : 0;

  function ghPinRail(track, onFrame) {
    if (!track || track.children.length < 2) return;
    var stage = track.parentNode;
    var wrap = stage && stage.parentNode;
    if (!stage || !wrap || !wrap.classList.contains('pinrail')) return;
    var bar = stage.querySelector('.pinbar i');
    var on = false, dist = 0, travel = 0, stageH = 0, topGap = 0;
    /* `cur` = chỗ thẻ ĐANG đứng, `tgt` = chỗ vị trí cuộn đòi. Bám đuổi kéo cái
       trước về cái sau; tắt bám đuổi thì hai số luôn bằng nhau. */
    var cur = 0, tgt = 0;

    function measure() {
      /* Đo lại từ số 0: xoá chiều cao/đỉnh đã đặt lần trước, nếu không
         offsetHeight trả về chính con số cũ mình vừa ghi vào. */
      stage.style.height = '';
      track.classList.add('gpin');
      dist = Math.max(0, track.scrollWidth - track.clientWidth);
      /* Không có gì để chạy ngang (màn rộng, ít thẻ) hoặc người dùng xin ít
         chuyển động → trả về lưới đứng yên, đừng kéo dài trang vô ích. */
      if (reduce || dist < 24) {
        on = false;
        cur = tgt = 0;
        track.classList.remove('gpin');
        wrap.classList.remove('is-pinned');
        wrap.style.height = '';
        stage.style.top = '';
        track.style.transform = '';
        if (bar) bar.style.width = '';
        if (onFrame) onFrame(0, false);
        return;
      }
      on = true;
      /* Quãng CUỘN cần để chạy hết quãng NGANG. Chia cho hệ số truyền ⇒ hệ số
         nhỏ hơn 1 nghĩa là phải cuộn dài hơn, mỗi nấc đi ít hơn. */
      travel = dist / PIN_GEAR;
      stageH = stage.offsetHeight;
      /* Chừa chỗ cho thanh tab dính ở đỉnh trang: còn dư thì căn giữa, chật
         thì ít nhất cũng đẩy xuống dưới thanh tab. */
      var free = innerHeight - stageH;
      topGap = free <= 0 ? 0 : Math.max(Math.min(free, 84), Math.round(free / 2));
      wrap.classList.add('is-pinned');
      wrap.style.height = (stageH + travel) + 'px';
      stage.style.top = topGap + 'px';
      stage.style.height = stageH + 'px';
      /* Đo lại (đổi khổ màn, lọc video…) thì đặt thẳng, đừng để thẻ trượt một
         quãng dài từ chỗ cũ sang chỗ mới. */
      cur = tgt = read();
      if (onFrame) onFrame(dist ? cur / dist : 0, true);
      paint();
    }

    /* ĐỌC vị trí cuộn → quãng ngang phải đi. Chỉ đọc, không ghi. */
    function read() {
      var p = (topGap - wrap.getBoundingClientRect().top) / travel;
      return (p < 0 ? 0 : (p > 1 ? 1 : p)) * dist;
    }
    /* GHI. Tách hẳn khỏi phần đọc để trong một khung hình mọi cú đọc hình học
       (kể cả onFrame) đều xong TRƯỚC mọi cú ghi — đọc sau khi ghi là ép trình
       duyệt tính lại bố cục ngay giữa khung hình, đúng cái làm dải giật. */
    function paint() {
      track.style.transform = 'translate3d(' + (-cur).toFixed(2) + 'px,0,0)';
      if (bar) bar.style.width = (dist ? cur / dist * 100 : 0).toFixed(2) + '%';
    }

    var raf = 0, last = 0;
    function step(ts) {
      raf = 0;
      if (!on) return;
      var dt = last ? Math.min((ts - last) / 1000, 1 / 20) : 1 / 60;
      last = ts;
      tgt = read();
      /* Dải nào cần biết mình đang trôi tới đâu (dải video sáng chip dự án) thì
         nhận lại tiến độ ở đây — ĐỌC, nên phải gọi trước paint(). */
      if (onFrame) onFrame(dist ? cur / dist : 0, true);
      var d = tgt - cur;
      if (!PIN_EASE || Math.abs(d) < 0.3) { cur = tgt; last = 0; }
      else {
        /* Hồi theo hàm mũ dựa trên dt thật, không phải hệ số cố định mỗi khung
           hình: máy 120Hz và máy 60Hz phải ra cùng một nhịp tay. */
        cur += d * (1 - Math.exp(-dt / PIN_EASE));
        raf = requestAnimationFrame(step);
      }
      paint();
    }
    function kick() { if (on && !raf) raf = requestAnimationFrame(step); }

    addEventListener('scroll', kick, { passive: true });

    /* Gộp mọi lý do phải đo lại vào một khung hình — ảnh trong thẻ tải xong,
       phông chữ về, xoay máy… đều đổi chiều cao dải. */
    var pend = 0;
    function remeasure() {
      if (pend) return;
      pend = requestAnimationFrame(function () { pend = 0; measure(); });
    }
    addEventListener('resize', remeasure);
    addEventListener('load', remeasure);
    if (window.ResizeObserver) new ResizeObserver(remeasure).observe(track);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(remeasure);
    measure();
    /* Dải nào có số thẻ đổi theo thao tác (dải video: lọc dự án, tìm kiếm) thì
       gọi remeasure() — xem renderVideos(). */
    return { remeasure: remeasure };
  }

  /* Chế độ sửa: ba dải thẻ nằm YÊN thành lưới — dải đang dính và dịch ngang
     thì không ngắm trúng thẻ nào để bấm vào sửa. */
  if (!EDITING) {
    ghPinRail(document.querySelector('.steps'));
    ghPinRail(document.querySelector('.creedgrid'));
    /* Dải video: trên điện thoại khối này bị chồng-ảnh thay thế (#video.deckon
       giấu #vgrid) ⇒ đo ra dist = 0 và ghPinRail tự trả về lưới đứng yên. */
    GH_VRAIL = ghPinRail(vg, function (p, pinned) {
      if (pinned) markCurrentProject(); else clearCurrentProject();
    }) || null;
  }

  /* Dải dự án (coverflow) tự chuyển sau 3 giây, dừng khi có người đụng vào. */
  (function autoCoverflow() {
    if (reduce || EDITING || list.length < 2) return;
    var wrap = document.querySelector('.cf-wrap');
    var idle = 0;
    var hold = function (ms) { idle = Date.now() + (ms || 6000); };
    ['pointerdown', 'pointerenter'].forEach(function (ev) {
      if (wrap) wrap.addEventListener(ev, function (e) { if (ev === 'pointerdown' || e.pointerType === 'mouse') hold(); });
    });
    if (wrap) wrap.addEventListener('pointerleave', function () { idle = 0; });
    setInterval(function () {
      if (document.hidden || Date.now() < idle) return;
      go(cur + 1);
    }, 3000);
  })();

  /* ---------- LEAD dùng chung cho MỌI ô nhận thông tin ----------
     Trang có HAI chỗ khách để lại số: form cuối trang (.leadR) và thiệp mời nổi
     (#hailform). Trước đây thiệp mời KHÔNG gửi gì cả — nó chép tên/số xuống form
     dưới rồi cuộn khách tới đó, khách tưởng đã gửi mà thật ra chưa (chú thích cũ
     ghi "chưa có endpoint" — endpoint đã có từ Đợt 4, đoạn kia bị bỏ quên).
     Nay cả hai đi qua ĐÚNG một hàm này và nhận ĐÚNG một lời cảm ơn. */
  var GHLead = (function () {
    var url = (SITE.assets && SITE.assets.leadUrl) || '';

    /* Lời cảm ơn anh Bình chốt 15/08 — để nguyên trong template chứ không qua
       defaults: hai chỗ phải giống nhau từng chữ, tách ra dữ liệu là sớm muộn
       cũng lệch. Sửa ở đây là đổi cả hai nơi. */
    var THANKS = {
      title: 'Đã nhận thông tin ✨',
      lines: [
        'Cảm ơn anh/chị đã tin tưởng kết nối.',
        'Tôi đã nhận được thông tin và sẽ trực tiếp liên hệ để hỗ trợ anh/chị trong thời gian sớm nhất.'
      ],
      pill: 'Bảo mật thông tin · Tư vấn tận tâm · Đồng hành trước, trong và sau giao dịch',
      bye: 'Hẹn gặp anh/chị!'
    };

    var esc = function (s) {
      return String(s).replace(/[&<>"]/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
      });
    };

    /* Trả về MARKUP chứ không phải node: hai nơi gắn vào hai chỗ khác nhau
       (thay ruột thẻ thiệp / thay ruột cột form) nên để bên gọi tự quyết. */
    var thanksHTML = function () {
      return '<div class="ghthanks" role="status">'
        + '<div class="tick"><svg viewBox="0 0 24 24"><path d="M4 12.5l5.2 5.2L20 7"/></svg></div>'
        + '<h3>' + esc(THANKS.title) + '</h3>'
        + THANKS.lines.map(function (t) { return '<p>' + esc(t) + '</p>'; }).join('')
        + '<div class="pill">' + esc(THANKS.pill) + '</div>'
        + '<div class="bye">' + esc(THANKS.bye) + '</div>'
        + '</div>';
    };

    /* Gửi thật. Trả Promise để bên gọi tự lo phần hiển thị của mình.
       `enteredVia` cho biết khách điền ở form hay ở thiệp mời — cùng một CRM,
       nhưng đọc báo cáo mới biết đường nào ra khách. */
    var send = function (payload) {
      if (!url) return Promise.reject(new Error('Trang chưa nối được nơi nhận thông tin.'));
      return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
        .then(function (r) {
          return r.json().catch(function () { return {}; }).then(function (d) { return { ok: r.ok, d: d }; });
        })
        .then(function (res) {
          if (!res.ok) throw new Error((res.d && res.d.message) || 'Gửi không thành công');
          return res.d || {};
        });
    };

    return { url: url, send: send, thanksHTML: thanksHTML };
  })();

  /* ---------- thiệp mời liên hệ sau 5 giây ---------- */
  (function hail() {
    var box = document.getElementById('hail');
    if (!box) return;
    /* Đang sửa trang thì thiệp mời cứ 5 giây nhảy ra một lần chỉ gây vướng. */
    if (EDITING) { box.remove(); return; }
    var KEY = 'gh-hail-closed';
    try { if (sessionStorage.getItem(KEY)) { box.remove(); return; } } catch (e) { /* chế độ riêng tư */ }

    var open = function () { box.classList.add('on'); };
    var close = function () {
      box.classList.remove('on');
      try { sessionStorage.setItem(KEY, '1'); } catch (e) { /* bỏ qua */ }
    };

    // Đang đứng ngay ở khối để lại thông tin thì đừng chen ngang; đợi lượt sau.
    var timer = setTimeout(function tick() {
      var form = document.getElementById('dangky');
      var r = form && form.getBoundingClientRect();
      if (r && r.top < innerHeight && r.bottom > 0) { timer = setTimeout(tick, 4000); return; }
      open();
    }, 5000);

    document.getElementById('hailx').addEventListener('click', function () { clearTimeout(timer); close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && box.classList.contains('on')) close(); });
    // Bấm ra ngoài thẻ (vào màn phủ) cũng đóng — thói quen của mọi hộp thoại.
    box.addEventListener('click', function (e) { if (e.target === box) close(); });
    // Bấm "Gọi ngay" là xong việc của thiệp.
    box.querySelector('.call').addEventListener('click', close);

    /* GỬI THẬT ngay tại thiệp (anh Bình chốt 15/08). Bản cũ chỉ chép tên/số
       xuống form cuối trang rồi cuộn khách tới đó — khách bấm "Kết nối với tôi"
       xong thấy mình bị đẩy đi đâu đó, không một lời xác nhận, và trong CRM thì
       KHÔNG có gì cả. Nay gửi thẳng rồi thay ruột thiệp bằng lời cảm ơn. */
    var hform = document.getElementById('hailform');
    var hbtn = hform.querySelector('button[type=submit]');
    var hmsg = document.createElement('p');
    hmsg.className = 'leadmsg';
    hmsg.setAttribute('role', 'status');
    hform.appendChild(hmsg);
    var hbusy = false;

    /* Ô bẫy bot — thiệp cũng là một cửa vào công khai, bot quét được cả cái này. */
    var hhp = document.createElement('input');
    hhp.type = 'text'; hhp.name = '__hp'; hhp.tabIndex = -1; hhp.autocomplete = 'off';
    hhp.setAttribute('aria-hidden', 'true');
    hhp.style.cssText = 'position:absolute;left:-9999px;width:1px;height:1px;opacity:0';
    hform.appendChild(hhp);

    hform.addEventListener('submit', function (e) {
      e.preventDefault();
      if (hbusy) return;
      var name = document.getElementById('hailname').value.trim();
      var phone = document.getElementById('hailphone').value.trim();
      if (!phone) {
        hmsg.textContent = 'Anh/chị để lại số điện thoại giúp tôi nhé.';
        hmsg.className = 'leadmsg err';
        document.getElementById('hailphone').focus();
        return;
      }

      hbusy = true;
      if (hbtn) { hbtn.disabled = true; hbtn.style.opacity = '.7'; }
      hmsg.textContent = 'Đang gửi…';
      hmsg.className = 'leadmsg';

      GHLead.send({ name: name, phone: phone, enteredVia: 'popup', __hp: hhp.value })
        .then(function () {
          /* Thay RUỘT thẻ, giữ nguyên thẻ và nút đóng: khách đọc xong tự đóng,
             không bị cướp mất màn hình. Thiệp không bật lại trong phiên này. */
          var card = box.querySelector('.hcard');
          var x = card.querySelector('.x');
          card.innerHTML = '';
          if (x) card.appendChild(x);
          card.insertAdjacentHTML('beforeend', GHLead.thanksHTML());
          try { sessionStorage.setItem(KEY, '1'); } catch (err) { /* chế độ riêng tư */ }
        })
        .catch(function (err) {
          hmsg.textContent = err.message || 'Chưa gửi được, anh/chị thử lại giúp tôi nhé.';
          hmsg.className = 'leadmsg err';
          if (hbtn) { hbtn.disabled = false; hbtn.style.opacity = ''; }
          hbusy = false;
        });
    });
  })();

  /* ---------- gửi form: lead đổ thẳng vào CRM của chủ trang ----------
     POST tới `assets.leadUrl` (= server.g-hub.vn/public/nguoi/<đường-dẫn>/lien-he).
     Server tra đường dẫn → chủ trang → tạo khách trong CRM của CHÍNH người đó,
     gắn NHÃN bằng đường dẫn trang. Không có đoạn này thì khách điền xong không
     đi đâu cả — đúng nghĩa mất khách. */
  (function leadForm() {
    var form = document.querySelector('.leadR');
    if (!form) return;
    var btn = form.querySelector('.bigsend');
    var fields = form.querySelectorAll('.fld input');
    var sel = form.querySelector('#fproject');

    /* Ô bẫy bot: người thật không thấy nên không bao giờ điền; bot điền mọi ô.
       Server thấy ô này có chữ thì im lặng bỏ qua. */
    var hp = document.createElement('input');
    hp.type = 'text'; hp.name = '__hp'; hp.tabIndex = -1; hp.autocomplete = 'off';
    hp.setAttribute('aria-hidden', 'true');
    hp.style.cssText = 'position:absolute;left:-9999px;width:1px;height:1px;opacity:0';
    form.appendChild(hp);

    var msg = document.createElement('p');
    msg.className = 'leadmsg';
    msg.setAttribute('role', 'status');
    if (btn) btn.insertAdjacentElement('afterend', msg);

    var say = function (text, tone) {
      msg.textContent = text || '';
      msg.className = 'leadmsg' + (tone ? ' ' + tone : '');
    };
    var busy = false;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      /* Trong trình sửa thì KHÔNG gửi thật — chủ trang bấm thử một cái là tự
         tạo một khách rác trong CRM của chính mình. */
      if (window.__GH_EDIT__) { say('Đang ở chế độ xem thử — form không gửi.', 'warn'); return; }
      if (busy || !GHLead.url) return;

      var name = (fields[0] && fields[0].value || '').trim();
      var phone = (fields[1] && fields[1].value || '').trim();
      var email = (fields[2] && fields[2].value || '').trim();
      if (!phone && !email) {
        say('Bạn để lại số điện thoại hoặc email giúp tôi nhé.', 'err');
        if (fields[1]) fields[1].focus();
        return;
      }

      var needs = [];
      form.querySelectorAll('.needs .need[aria-pressed="true"]').forEach(function (b) { needs.push(b.textContent.trim()); });
      var project = sel && sel.selectedIndex > 0 ? sel.value : '';

      busy = true;
      if (btn) { btn.disabled = true; btn.style.opacity = '.7'; }
      say('Đang gửi…');

      GHLead.send({
        name: name, phone: phone, email: email,
        project: project, needs: needs,
        enteredVia: 'form', __hp: hp.value
      })
        .then(function () {
          /* Thay CẢ khối form bằng lời cảm ơn — giống hệt thiệp mời. Trước đây
             chỉ đổi một dòng chữ nhỏ dưới nút, khách vừa điền xong nhìn vẫn thấy
             nguyên cái form nên hay bấm gửi lần nữa. */
          form.innerHTML = GHLead.thanksHTML();
        })
        .catch(function (err) {
          say(err.message || 'Chưa gửi được, anh/chị thử lại giúp tôi nhé.', 'err');
          if (btn) { btn.disabled = false; btn.style.opacity = ''; }
          busy = false;
        });
    });
  })();

  /* ---------- chế độ sửa tại chỗ ----------
     Bật bằng `?edit=1` (màn quản lý nhúng trang này trong iframe). Mỗi vùng
     được phủ một nút Sửa; bấm là báo ra khung cha bằng postMessage kèm KHOÁ
     vùng — khung cha mở đúng form, không cần đoán theo toạ độ. */
  (function editMode() {
    /* Nhúng bằng srcdoc thì không có query và history.replaceState ném lỗi vì
       origin mờ — nên nhận thêm cờ toàn cục do khung cha đặt sẵn. */
    if (!EDITING) return;
    document.documentElement.classList.add('gh-edit');
    var PEN = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 20h4l10-10-4-4L4 16z"/><path d="M14 6l4 4"/></svg>';
    var CAM = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 8h3l1.5-2h7L17 8h3v11H4z"/><circle cx="12" cy="13" r="3.4"/></svg>';
    var post = function (msg) { msg.source = 'gh-personal-site'; parent.postMessage(msg, '*'); };

    /* Thẻ báo trạng thái lưu — thay cho toast của app, vì trang chạy trong iframe. */
    var chipEl = document.createElement('div');
    chipEl.className = 'gh-chip';
    document.body.appendChild(chipEl);
    var chipT = 0;
    var chip = function (text, err) {
      chipEl.textContent = text;
      chipEl.classList.toggle('err', !!err);
      chipEl.classList.add('on');
      clearTimeout(chipT);
      chipT = setTimeout(function () { chipEl.classList.remove('on'); }, 2400);
    };

    /* Ghi một giá trị vào SITE theo đường dẫn "video.items.2.title" — giữ DOM
       và dữ liệu trong đầu đồng bộ, không cần dựng lại trang sau mỗi lần lưu. */
    var setSite = function (path, value) {
      var segs = String(path).split('.');
      var node = SITE;
      for (var i = 0; i < segs.length - 1; i++) {
        var k = /^\d+$/.test(segs[i]) ? Number(segs[i]) : segs[i];
        if (node[k] == null) node[k] = /^\d+$/.test(segs[i + 1] || '') ? [] : {};
        node = node[k];
      }
      var last = segs[segs.length - 1];
      node[/^\d+$/.test(last) ? Number(last) : last] = value;
    };

    /* ---------- nút "Sửa <vùng>" (mở form đầy đủ của vùng — cho các ô sâu:
       liên kết, icon, thêm/bớt mục danh sách…) ---------- */
    document.querySelectorAll('[data-gh-section]').forEach(function (el) {
      var key = el.getAttribute('data-gh-section');
      var label = el.getAttribute('data-gh-label') || 'phần này';
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'gh-editbtn';
      btn.innerHTML = PEN + ' Sửa ' + label;
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        post({ action: 'edit', key: key, label: label });
      });
      el.appendChild(btn);
    });

    /* ---------- 1) CHỮ: bấm thẳng vào là sửa, không mở form ---------- */
    var pathOf = function (el) {
      return el.getAttribute('data-inline') || el.getAttribute('data-inline-html') ||
             el.getAttribute('data-f') || el.getAttribute('data-fhtml');
    };
    var isRich = function (el) { return el.hasAttribute('data-inline-html') || el.hasAttribute('data-fhtml'); };
    /* Field HỆ THỐNG (tên, chức danh, SĐT…) sửa ở Hồ sơ, không sửa ở đây. */
    var SKIP = { 'seo.title': 1, 'profile.name': 1, 'profile.initials': 1, 'profile.role': 1, 'footer.copyright': 1 };
    document.querySelectorAll('[data-f],[data-fhtml],[data-inline],[data-inline-html]').forEach(function (el) {
      if (el.tagName === 'TITLE' || el.hasAttribute('data-fkeep') || el.hasAttribute('data-fbg')) return;
      var p = pathOf(el);
      if (!p || SKIP[p]) return;
      /* Danh sách bị LỌC lúc dựng (thiếu dữ liệu Hồ sơ thì mục biến mất) ⇒ chỉ
         số trên trang lệch mảng đã lưu. Nay TPL gắn `_i` = chỗ đứng thật nên
         intro.facts sửa tại chỗ được; contact./profile.actions vẫn đứng ngoài
         vì đó là nút hệ thống (số điện thoại, Zalo, email từ Hồ sơ). */
      if (p.indexOf('contact.') === 0 || p.indexOf('profile.actions') === 0) return;
      el.classList.add('gh-txt');
    });

    var editingEl = null;
    var commitEdit = function (cancel) {
      var el = editingEl;
      if (!el) return;
      editingEl = null;
      el.classList.remove('gh-on');
      el.removeAttribute('contenteditable');
      var orig = el.dataset.ghOrig || '';
      delete el.dataset.ghOrig;
      if (cancel) {
        if (isRich(el)) el.innerHTML = orig; else el.textContent = orig;
        return;
      }
      var val = isRich(el) ? el.innerHTML : el.textContent;
      if (val === orig) return;
      var p = pathOf(el);
      setSite(p, val);
      post({ action: 'save-field', path: p, value: val, rich: isRich(el) });
      chip('Đang lưu…');
    };
    var startEdit = function (el) {
      if (editingEl === el) return;
      commitEdit();
      editingEl = el;
      el.dataset.ghOrig = isRich(el) ? el.innerHTML : el.textContent;
      /* plaintext-only: dán từ Word không lôi cả rừng thẻ vào ô chữ trơn. */
      try { el.contentEditable = isRich(el) ? 'true' : 'plaintext-only'; } catch (e) { el.contentEditable = 'true'; }
      if (!el.isContentEditable) el.contentEditable = 'true'; // Firefox chưa có plaintext-only
      el.classList.add('gh-on');
      el.focus();
    };
    document.addEventListener('keydown', function (e) {
      if (!editingEl) return;
      if (e.key === 'Escape') { e.preventDefault(); commitEdit(true); return; }
      /* Enter chốt (ô một dòng); ô định dạng thì Ctrl/Cmd+Enter chốt. */
      if (e.key === 'Enter' && (!isRich(editingEl) || e.metaKey || e.ctrlKey)) { e.preventDefault(); editingEl.blur(); }
    });
    document.addEventListener('focusout', function (e) {
      if (e.target === editingEl) setTimeout(function () { if (editingEl && document.activeElement !== editingEl) commitEdit(); }, 0);
    });

    /* Một tay gác cửa duy nhất: chữ sửa được thì vào chế độ gõ; còn lại mọi liên
       kết đều vô hiệu — bấm nhầm là văng khỏi trình sửa. */
    document.addEventListener(
      'click',
      function (e) {
        if (!e.target.closest) return;
        var t = e.target.closest('.gh-txt');
        if (t) {
          if (t !== editingEl) { e.preventDefault(); e.stopPropagation(); startEdit(t); }
          else e.stopPropagation();
          return;
        }
        var a = e.target.closest('a');
        if (a && a !== editingEl) { e.preventDefault(); e.stopPropagation(); }
      },
      true,
    );

    /* ---------- 2) ẢNH: nút máy ảnh đè ngay trên ảnh ---------- */
    var pick = function (target, kind) { post({ action: 'pick-media', target: target, kind: kind || 'image' }); };
    var imgButton = function (host, label, onClick, pos) {
      if (!host) return null;
      host.classList.add('gh-imgwrap');
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'gh-imgbtn';
      b.innerHTML = CAM + ' ' + label;
      if (pos) b.style.cssText = pos;
      else b.style.cssText = 'right:10px;bottom:10px';
      b.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); onClick(b); });
      host.appendChild(b);
      return b;
    };

    var coverEl = document.querySelector('.cover[data-fbg]');
    imgButton(coverEl, 'Đổi ảnh bìa', function () { pick('cover.image', 'image'); });
    imgButton(coverEl, 'Video bìa', function () { pick('cover.video', 'video'); }, 'right:10px;bottom:52px');
    var unVidBtn = imgButton(coverEl, 'Gỡ video bìa', function () {
      setSite('cover.video', '');
      setCoverVideo('');
      post({ action: 'save-field', path: 'cover.video', value: '' });
      chip('Đang gỡ video bìa…');
      if (unVidBtn) unVidBtn.style.display = 'none';
    }, 'right:10px;bottom:94px');
    if (unVidBtn && !(SITE.cover && SITE.cover.video)) unVidBtn.style.display = 'none';

    /* Chọn theo data-fbg chứ đừng theo class khối bọc — bố cục thẻ giới thiệu
       đã đổi tên khối một lần (.id → .idrow/.ava) và nút này biến mất theo. */
    imgButton(document.querySelector('.avatar[data-fbg="profile.avatar"]'), 'Đổi ảnh', function () { pick('profile.avatar', 'image'); },
      'left:50%;bottom:8px;transform:translateX(-50%)');

    document.querySelectorAll('[data-imgedit]').forEach(function (el) {
      imgButton(el, 'Đổi ảnh', function () { pick(el.getAttribute('data-imgedit'), 'image'); },
        'left:50%;top:50%;transform:translate(-50%,-50%)');
    });

    /* Đổi/gỡ video bìa ngay tại chỗ — dựng lại thẻ <video> đúng như bộ nạp. */
    var setCoverVideo = function (url) {
      var host = document.querySelector('[data-fvideo="cover"]');
      if (!host) return;
      var old = host.querySelector('.covervid');
      if (old) old.remove();
      host.classList.remove('has-vid');
      host.classList.remove('vid-on');
      if (!url) return;
      var v = document.createElement('video');
      v.className = 'covervid';
      v.muted = true; v.defaultMuted = true;
      v.setAttribute('muted', ''); v.setAttribute('playsinline', '');
      v.autoplay = true; v.loop = true; v.playsInline = true; v.preload = 'metadata';
      if (SITE.cover && SITE.cover.posterImage) v.poster = SITE.cover.posterImage;
      v.src = url;
      /* Lớp video mờ cho tới khi có khung hình đầu (xem CSS `.vid-on`) — trong
         trình sửa cũng phải mở ra, không thì chọn xong video mà bìa vẫn đứng im
         ở ảnh nền, người sửa tưởng chưa ăn. */
      var reveal = function () { host.classList.add('vid-on'); };
      v.addEventListener('loadeddata', reveal);
      v.addEventListener('playing', reveal);
      host.insertBefore(v, host.firstChild);
      host.classList.add('has-vid');
      var p = v.play();
      if (p && p.catch) p.catch(function () {});
    };

    /* ---------- 3) VIDEO: thêm / sửa / xoá ngay trong khối video ---------- */
    SITE.video = SITE.video || {};
    var vsec = document.getElementById('video');
    var vgridEl = document.getElementById('vgrid');
    var panel = document.createElement('div');
    panel.className = 'gh-vpanel';
    panel.innerHTML =
      '<div class="row one"><div><label>Liên kết video — YouTube / Vimeo / TikTok / link .mp4</label>' +
      '<input class="f-url" placeholder="https://youtube.com/watch?v=… · https://tiktok.com/@… — dán link là ảnh bìa tự lấy theo link"></div></div>' +
      '<div class="thumbrow"><span class="thumb"></span>' +
      '<button type="button" class="mini up-video">Tải video lên</button>' +
      '<button type="button" class="mini up-thumb">Đổi ảnh bìa</button>' +
      '<span class="hint">Ảnh bìa và thời lượng đều do hệ thống tự lấy theo link/tệp — YouTube, Vimeo, TikTok (nhận cả link rút gọn vm.tiktok.com) và video tải lên. Video tải lên thì bấm "Đổi ảnh bìa" để chọn ảnh.</span></div>' +
      '<div class="row f-r1"><div><label>Tiêu đề</label><input class="f-title" placeholder="Đi thực tế The Magnolia — đánh giá căn 2PN"></div>' +
      '<div><label>Dự án</label><select class="f-proj"></select></div></div>' +
      '<div class="row f-r2"><div><label>Mô tả ngắn</label><input class="f-sub" placeholder="Pháp lý · giá thực trả · tiến độ"></div>' +
      '<div><label>Thời lượng (hệ thống tự lấy)</label><input class="f-dur" readonly tabindex="-1" placeholder="tự lấy theo link / tệp video"></div></div>' +
      '<div class="acts"><button type="button" class="no">Huỷ</button><button type="button" class="ok">Lưu video</button></div>';
    var addBtn = document.createElement('button');
    addBtn.type = 'button';
    addBtn.className = 'gh-addvideo';
    addBtn.innerHTML = '<span class="ic">＋</span><span>Thêm video — dán link nhúng hoặc tải lên</span>';
    /* Lưới video nay nằm TRONG lớp bọc .pinrail (dải chạy ngang) ⇒ chèn theo
       lớp bọc, không theo chính #vgrid: insertBefore đòi nút mốc phải là con
       TRỰC TIẾP của #video, lấy nhầm là ném NotFoundError và chết cả trình sửa. */
    var vAnchor = (vgridEl && vgridEl.closest ? vgridEl.closest('.pinrail') : null) || vgridEl;
    if (vsec && vAnchor && vAnchor.parentNode === vsec) {
      vsec.insertBefore(addBtn, vAnchor);
      vsec.insertBefore(panel, vAnchor);
    }
    var q = function (sel) { return panel.querySelector(sel); };
    var vMode = 'item';
    var vEditIdx = null;
    var vDraft = {};
    var setThumb = function (url) {
      vDraft.img = url || '';
      q('.thumb').style.backgroundImage = url ? "url('" + url + "')" : '';
    };
    var openPanel = function (mode, idx) {
      vMode = mode;
      vEditIdx = idx == null ? null : idx;
      var src = mode === 'hero' ? (SITE.video.hero || {}) : ((SITE.video.items || [])[vEditIdx] || {});
      /* Video nổi bật: link + ảnh + thời lượng + DỰ ÁN (anh Bình chốt 17/08 —
         chip dự án hiện ở cột chữ bên phải). Tiêu đề/mô tả ngắn thì không có:
         chữ của video nổi bật chính là chú thích sửa thẳng ngoài trang. */
      q('.f-title').parentElement.style.display = mode === 'hero' ? 'none' : '';
      q('.f-r1').classList.toggle('one', mode === 'hero');
      q('.f-sub').parentElement.style.display = mode === 'hero' ? 'none' : '';
      q('.f-r2').classList.toggle('one', mode === 'hero');
      q('.f-url').value = src.url || '';
      q('.f-title').value = src.title || '';
      q('.f-sub').value = src.sub || '';
      q('.f-dur').value = src.dur || '';
      q('.f-dur').dataset.auto = '0'; /* dur đã lưu coi như của người dùng */
      durSeq++; /* huỷ mọi probe đang chạy dở của lần mở trước */
      var sel = q('.f-proj');
      sel.innerHTML = '';
      var o0 = document.createElement('option'); o0.value = ''; o0.textContent = '— Không gắn dự án —'; sel.appendChild(o0);
      P.forEach(function (p) { var o = document.createElement('option'); o.value = p.n; o.textContent = p.n; sel.appendChild(o); });
      sel.value = src.proj || '';
      setThumb(coverImg(src.img));
      panel.classList.add('on');
      panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };
    var closePanel = function () { panel.classList.remove('on'); vDraft = {}; durSeq++; };
    /* Tự dò thời lượng điền ô "Thời lượng": YouTube qua iframe ẩn + postMessage
       (script IFrame API bị CSP của srcdoc chặn nên không dùng YT.Player được),
       Vimeo qua oEmbed, tệp mp4/tải lên đọc metadata. Người dùng tự gõ thì thôi. */
    var durSeq = 0, durTimer = null;
    var fmtDur = function (s) {
      s = Math.round(Number(s) || 0);
      if (s <= 0 || !isFinite(s)) return '';
      var p = function (n) { return (n < 10 ? '0' : '') + n; };
      var h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60);
      return (h ? h + ':' + p(m) : p(m)) + ':' + p(s % 60);
    };
    var fillDur = function (my, secs) {
      var t = fmtDur(secs);
      if (my !== durSeq || !t) return;
      var f = q('.f-dur');
      if (f.value.trim() && f.dataset.auto !== '1') return;
      f.value = t;
      f.dataset.auto = '1';
    };
    var ytDurProbe = function (id, my) {
      var fr = document.createElement('iframe');
      fr.style.cssText = 'position:fixed;left:-9999px;top:0;width:2px;height:2px;border:0';
      fr.setAttribute('allow', 'autoplay');
      fr.src = 'https://www.youtube-nocookie.com/embed/' + id + '?enablejsapi=1&autoplay=1&mute=1&controls=0';
      var done = false;
      var stop = function () {
        if (done) return;
        done = true;
        window.removeEventListener('message', onMsg);
        if (fr.parentNode) fr.parentNode.removeChild(fr);
      };
      var onMsg = function (e) {
        if (e.source !== fr.contentWindow) return;
        var d = e.data;
        if (typeof d === 'string') { try { d = JSON.parse(d); } catch (err) { return; } }
        var dur = d && d.info && Number(d.info.duration);
        if (dur > 0) { fillDur(my, dur); stop(); }
      };
      window.addEventListener('message', onMsg);
      fr.addEventListener('load', function () {
        try { fr.contentWindow.postMessage(JSON.stringify({ event: 'listening', id: 'ghdur', channel: 'widget' }), '*'); } catch (err) {}
      });
      document.body.appendChild(fr);
      setTimeout(stop, 12000);
    };
    var probeDur = function (url) {
      var my = ++durSeq;
      url = String(url || '').trim();
      if (!url) return;
      var y = ytId(url), v = vimeoId(url);
      if (y) { ytDurProbe(y, my); return; }
      /* TikTok: oEmbed không có thời lượng, mà dựng <video src=trang-html> thì
         trình duyệt tải cả trang rồi mới chịu thua. Máy chủ đọc hộ khối dữ liệu
         TikTok nhúng trong trang video (route `_tiktok/link`) rồi trả về giây. */
      if (tiktokId(url) || tiktokShort(url)) {
        var base = apiBase();
        if (!base) return;
        fetch(base + '/public/nguoi/_tiktok/link?u=' + encodeURIComponent(url))
          .then(function (r) { return r.ok ? r.json() : null; })
          .then(function (j) { if (j && j.duration) fillDur(my, j.duration); })
          .catch(function () {});
        return;
      }
      if (v) {
        fetch('https://vimeo.com/api/oembed.json?url=' + encodeURIComponent('https://vimeo.com/' + v))
          .then(function (r) { return r.json(); })
          .then(function (j) { if (j) fillDur(my, j.duration); })
          .catch(function () {});
        return;
      }
      var vd = document.createElement('video');
      vd.preload = 'metadata';
      vd.muted = true;
      vd.addEventListener('loadedmetadata', function () {
        fillDur(my, vd.duration);
        vd.removeAttribute('src'); vd.load();
      });
      vd.src = url;
    };
    var saveVideoSection = function (note) {
      post({ action: 'save-section', key: 'video', content: SITE.video });
      chip(note || 'Đang lưu…');
      refreshVideos();
    };
    /* Link rút gọn vm./vt.tiktok.com không mang id ⇒ nhờ máy chủ dịch ra link
       đầy đủ rồi thay ngay vào ô (chủ trang thấy link "thật" mình sắp lưu).
       `seq` chặn cảnh dán link A rồi đổi ý dán link B: câu trả lời về muộn của
       A không được phép đè link B đang gõ dở. */
    var ttSeq = 0;
    var resolveTikTok = function (raw) {
      var my = ++ttSeq;
      var base = apiBase();
      if (!base) return;
      fetch(base + '/public/nguoi/_tiktok/link?u=' + encodeURIComponent(raw))
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (j) {
          if (!j || !j.url || my !== ttSeq) return;
          if (q('.f-url').value.trim() !== raw) return;
          q('.f-url').value = j.url;
          setThumb(thumbFromUrl(j.url));
          if (!q('.f-title').value.trim() && j.title) q('.f-title').value = j.title;
          if (j.duration) fillDur(++durSeq, j.duration);
        })
        .catch(function () {});
    };
    q('.f-url').addEventListener('input', function () {
      var u = q('.f-url').value.trim();
      var t = thumbFromUrl(u);
      if (t) setThumb(t);
      else if (tiktokShort(u)) resolveTikTok(u);
      /* Đổi link = thời lượng lấy lại theo link (như ảnh bìa); gõ tay vào ô
         thời lượng SAU đó thì giữ nguyên tay. */
      q('.f-dur').dataset.auto = '1';
      if (durTimer) clearTimeout(durTimer);
      durTimer = setTimeout(function () { probeDur(u); }, 500);
    });
    /* Không còn móc "gõ tay" cho ô thời lượng: ô đã khoá (readonly), mọi giá
       trị đều do probeDur điền. Bản ghi cũ có thời lượng gõ tay vẫn giữ nguyên
       cho tới khi chủ trang dán lại link. */
    q('.up-video').addEventListener('click', function () { pick('__vdraft.video', 'video'); });
    q('.up-thumb').addEventListener('click', function () { pick('__vdraft.img', 'image'); });
    q('.no').addEventListener('click', closePanel);
    q('.ok').addEventListener('click', function () {
      var url = normVideoUrl(q('.f-url').value);
      var img = vDraft.img || thumbFromUrl(url);
      if (vMode === 'hero') {
        /* Không dán link nhưng ảnh bìa là thumbnail YouTube/Vimeo → suy lại
           link để nút phát chạy như các video thường. */
        SITE.video.hero = {
          img: img, dur: q('.f-dur').value.trim(), url: url || urlFromThumb(img), proj: q('.f-proj').value
        };
        closePanel();
        saveVideoSection();
        return;
      }
      if (!url) { chip('Dán link video hoặc bấm "Tải video lên" trước đã', true); return; }
      var item = {
        proj: q('.f-proj').value, title: q('.f-title').value.trim(), sub: q('.f-sub').value.trim(),
        dur: q('.f-dur').value.trim(), img: img, url: url
      };
      var items = (SITE.video.items || []).slice();
      if (vEditIdx == null) items.push(item);
      else items[vEditIdx] = Object.assign({}, items[vEditIdx], item);
      SITE.video.items = items;
      closePanel();
      saveVideoSection();
    });
    addBtn.addEventListener('click', function () { openPanel('item', null); });
    imgButton(vheroEl, 'Đổi ảnh bìa', function () { pick('video.hero.img', 'image'); });
    imgButton(vheroEl, 'Video nổi bật', function () { openPanel('hero'); }, 'right:10px;bottom:52px');
    GH_VHOOKS = {
      edit: function (idx) { openPanel('item', idx); },
      remove: function (idx) {
        var items = (SITE.video.items || []).slice();
        items.splice(idx, 1);
        SITE.video.items = items;
        saveVideoSection('Đã xoá — đang lưu…');
      }
    };
    renderVideos(); // dựng lại để mỗi thẻ video có nút Sửa/Xoá

    /* ---------- tin từ khung cha ---------- */
    var applyMedia = function (target, url) {
      if (String(target).indexOf('__vdraft.') === 0) {
        var field = target.split('.')[1];
        if (field === 'video') {
          q('.f-url').value = url;
          q('.f-dur').dataset.auto = '1';
          probeDur(url); /* video tải lên: đọc metadata điền thời lượng */
        } else setThumb(url);
        return;
      }
      setSite(target, url);
      if (target === 'cover.image') {
        if (coverEl) coverEl.style.backgroundImage = "url('" + url + "')";
      } else if (target === 'cover.video') {
        setCoverVideo(url);
        if (unVidBtn) unVidBtn.style.display = url ? '' : 'none';
      } else if (target === 'profile.avatar') {
        document.querySelectorAll('[data-fbg="profile.avatar"]').forEach(function (el) {
          el.style.backgroundImage = "url('" + url + "')";
          el.style.backgroundSize = 'cover';
          el.textContent = '';
        });
      } else if (String(target).indexOf('video.') === 0) {
        /* Đổi ảnh bìa video nổi bật bằng link video/thumbnail mà url đang
           trống → điền lại url từ chính giá trị vừa dán rồi lưu luôn. */
        if (target === 'video.hero.img') {
          var hv = SITE.video.hero || (SITE.video.hero = {});
          if (!hv.url) {
            var hu = (ytId(url) || vimeoId(url)) ? normVideoUrl(url) : urlFromThumb(url);
            if (hu) { hv.url = hu; post({ action: 'save-section', key: 'video', content: SITE.video }); }
          }
        }
        refreshVideos();
      } else if (String(target).indexOf('process.steps.') === 0) {
        var im = document.querySelector('[data-imgedit="' + target + '"] img');
        if (im) im.src = url;
      }
    };
    window.addEventListener('message', function (e) {
      var d = e.data;
      if (!d || d.source !== 'gh-personal-site-parent') return;
      // Cuộn tới đúng vùng khi khung cha yêu cầu (bấm ở danh sách bên ngoài).
      if (d.action === 'scrollTo') {
        var t = document.querySelector('[data-gh-section="' + d.key + '"]');
        if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
      if (d.action === 'saved') chip(d.ok ? 'Đã lưu ✓' : (d.message || 'Lưu không thành công'), !d.ok);
      if (d.action === 'media-picked') applyMedia(d.target, d.url);
    });

    post({ action: 'ready' });
  })();

  /* ---------- hiệu ứng ---------- */
  var items = document.querySelectorAll('.rv');
  if (reduce || !('IntersectionObserver' in window)) { items.forEach(function(el){ el.classList.add('in'); }); }
  else {
    var io = new IntersectionObserver(function(e){
      e.forEach(function(x){ if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); } });
    }, { rootMargin: '0px 0px -6% 0px', threshold: 0.04 });
    items.forEach(function(el){ io.observe(el); });
  }
  document.querySelectorAll('.need').forEach(function(b){
    b.addEventListener('click', function(){ b.setAttribute('aria-pressed', b.getAttribute('aria-pressed') === 'true' ? 'false' : 'true'); });
  });
  var tabs = Array.prototype.slice.call(document.querySelectorAll('.tab'));
  var targets = tabs.map(function(t){ return document.querySelector(t.getAttribute('href')); });
  if ('IntersectionObserver' in window) {
    var io3 = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if (!e.isIntersecting) return;
        var i = targets.indexOf(e.target); if (i < 0) return;
        tabs.forEach(function(t){ t.classList.remove('on'); });
        tabs[i].classList.add('on');
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    targets.forEach(function(el){ if (el) io3.observe(el); });
  }

  /* ---------- link neo = cuộn trong trang ----------
     Trong trình xem/sửa trang chạy qua iframe srcdoc: href="#duan" bị phân giải
     theo URL của trang MẸ, bấm một cái là iframe tải nguyên app vào trong chính
     nó (trang-trong-trang). Chặn lại và tự cuộn — ngoài trang thật cũng nhờ đó
     cuộn mượt thay vì nhảy giật. KHÔNG ghi hash (replaceState trong srcdoc ném
     SecurityError vì origin mờ). */
  document.addEventListener('click', function (e) {
    if (e.defaultPrevented) return; // nút đã có việc riêng (vd "Xem thêm video")
    var a = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!a) return;
    var href = a.getAttribute('href') || '';
    if (href.charAt(0) !== '#') return;
    e.preventDefault();
    var target = href.length > 1 ? document.getElementById(href.slice(1)) : null;
    if (target) target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
  });

  /* ---------- menu trôi ngang nhè nhẹ ----------
     Trên điện thoại thanh menu dài hơn màn hình. Cho nó trôi chậm sang trái để
     người xem biết còn mục phía sau, thay vì tưởng chỉ có 2–3 mục.
     Chỉ chạy khi THẬT SỰ có phần bị khuất (máy tính vừa đủ chỗ thì đứng yên). */
  (function tabDrift() {
    var bar = document.querySelector('.tabbar');
    if (!bar || reduce) return;

    var SPEED = 0.28; // px mỗi khung hình ≈ 17px/giây — đủ chậm để không rối mắt
    var PAUSE_END = 1400; // nghỉ ở hai đầu trước khi đổi chiều
    var IDLE_AFTER_TOUCH = 8000; // người ta tự vuốt thì để yên một lúc
    var raf = 0;
    var waitUntil = 0;
    var resumeAt = 0;
    var goingBack = false;

    var max = function () { return bar.scrollWidth - bar.clientWidth; };

    /* Vòng khung hình phải TẮT được, không chỉ "chạy rồi return".
       Bản cũ luôn tự đặt lịch lại nên nó thức 60 lần/giây suốt đời trang — kể
       cả trên máy tính, nơi thanh tab vừa khít nên chẳng có gì để trôi. Mỗi lần
       thức nó lại ghi `scrollLeft` của một khối DÍNH có kính mờ ⇒ máy phải vẽ
       lại thanh đó, cộng đúng vào lúc người xem đang cuộn (anh Bình báo giật
       17/08). Nay: hết việc là ngủ, có việc mới đánh thức. */
    var visible = true;

    var step = function (now) {
      raf = 0;
      if (document.hidden || !visible) return;               // ngủ, sẽ được gọi lại
      var m = max();
      if (m <= 4) return;    // vừa khít màn hình → không có gì để trôi, ngủ luôn
      raf = requestAnimationFrame(step);
      if (now < waitUntil || now < resumeAt) return;         // đang nghỉ, vẫn phải đếm giờ

      if (goingBack) {
        bar.scrollLeft -= SPEED * 2.2; // về đầu nhanh hơn một chút
        if (bar.scrollLeft <= 0) { bar.scrollLeft = 0; goingBack = false; waitUntil = now + PAUSE_END; }
      } else {
        bar.scrollLeft += SPEED;
        if (bar.scrollLeft >= m - 0.5) { goingBack = true; waitUntil = now + PAUSE_END; }
      }
    };
    var wake = function () { if (!raf && !document.hidden && visible) raf = requestAnimationFrame(step); };
    wake();

    /* Cuộn khỏi thanh tab (nó dính ở đỉnh nên hiếm, nhưng có lúc bị khối khác
       đè) hoặc đổi khổ màn → thức dậy đo lại. */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (e) {
        visible = !!(e[0] && e[0].isIntersecting);
        wake();
      }, { threshold: 0 }).observe(bar);
    }
    addEventListener('resize', wake);
    document.addEventListener('visibilitychange', wake);
    /* Thanh vừa khít lúc dựng thì vòng ngủ ngay — phông chữ về muộn hay thêm
       mục làm nó dài ra thì phải có ai đánh thức, nếu không nó ngủ mãi. */
    if (window.ResizeObserver) new ResizeObserver(wake).observe(bar);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(wake);

    var hold = function (ms) { resumeAt = performance.now() + ms; wake(); };
    ['touchstart', 'pointerdown', 'wheel'].forEach(function (ev) {
      bar.addEventListener(ev, function () { hold(IDLE_AFTER_TOUCH); }, { passive: true });
    });
    bar.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') hold(IDLE_AFTER_TOUCH); });
  })();
})();

</script>
{/literal}
