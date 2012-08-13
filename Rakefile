require "uglifier" # gem install uglifier

desc "prepare javascript files for production"
task "prepare:js" do
	Dir.glob("js/*.js").each do |file|
		continue unless file.match /\.dev\./
		puts "> preparing #{file}"

		# minify
		minified = Uglifier.compile File.read(file)

		# write to production file
		new_file = "#{file.split(".")[0]}.js"
		File.open(new_file, 'w') { |f| f.write minified }
	end
end